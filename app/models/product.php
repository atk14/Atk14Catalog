<?php
class Product extends ApplicationModel implements Translatable,Rankable{
	
	static function GetTranslatableFields(){
		return array(
			"label", // označení varianty, e.g "XL", "50g", "32GB"
			"name",
			"description",
		);
	}

	static function GetInstanceByCatalogId($catalog_id){
		($product = Product::FindByCatalogId($catalog_id,array("use_cache" => true))) ||
		($product = Product::FindFirst(array(
			"conditions" => "deleted='t' AND catalog_id LIKE :catalog_id||'~%'",
			"bind_ar" => array(":catalog_id" => $catalog_id),
			"use_cache" => true
		)));
		return $product;
	}

	function getName($lang = null,$with_label = true){
		if(is_bool($lang)){
			$with_label = $lang;
			$lang = null;
		}

		$name = parent::getName($lang);

		if(strlen($name)>0){
			return $name;
		}

		$card = $this->getCard();
		$name = $card->getName($lang);

		if($with_label){
			if($label = $this->getLabel($lang)){
				$name .= ", ".$label;
			}
		}

		return $name;
	}

	/**
	 *
	 * Alias for ```Product::getName($lang,true);```
	 */
	function getFullName($lang = null){
		return $this->getName($lang,true);
	}

	function getCard(){ return Cache::Get("Card",$this->getCardId()); }

	function getCatalogId(){
		$catalog_id = $this->g("catalog_id");
		if($this->isDeleted()){
			// 123/456789~deleted-444 -> 123/456789
			$catalog_id = preg_replace('/~deleted-\d+$/','',$catalog_id);
		}
		return $catalog_id;
	}
	
	function getImages(){
		return ProductImage::GetImages($this);
	}

	/**
	 *
	 * $image = $product->getImage(false);
	 */
	function getImage($options = []){
		if(is_bool($options)){
			$options = ["consider_card_image" => $options];
		}
		$options += [
			"consider_card_image" => true,
		];

		if($images = $this->getImages()){
			return $images[0];
		}

		if(!$options["consider_card_image"]){
			return;
		}

		$card = $this->getCard();
		if($images = $card->getImages(array("consider_product_images" => false))){
			return $images[0];
		}
	}

	function setRank($new_rank){
		return $this->_setRank($new_rank,array(
			"card_id" => $this->getCardId(),
			"deleted" => false,
		));
	}

	function isDeleted(){ return $this->getDeleted(); }
	function isVisible(){ return $this->getVisible(); }

	function destroy($delete_for_real = false){
		if($delete_for_real){
			return parent::destroy($delete_for_real);
		}

		if($this->isDeleted()){
			return null;
		}

		$this->s(array(
			"deleted" => true,
			"catalog_id" => sprintf("%s~deleted-%s",$this->getCatalogId(),$this->getId()),
		));
	}

	function getSiblings() {
		$products = $this->getCard()->getProducts();
		$_siblings = array();
		foreach($products as $_p) {
			if ($_p->getId()==$this->getId()) {
				continue;
			}
			$_siblings[] = $_p;
		}
		return $_siblings;
	}

	function getSuppliesLister() {
		return $this->getLister("Products", array(
			"table_name" => "supplies",
			"owner_field_name" => "device_product_id",
		));
	}

	/**
	 * Spotrebni material pro tento produkt.
	 *
	 * @return Product
	 */
	function getSupplies() {
		$supplies_lister = $this->getSuppliesLister();
		return $supplies_lister->getRecords();
	}

	function getDevicesLister() {
		return $this->getLister("Products", array(
			"table_name" => "supplies",
			"owner_field_name" => "product_id",
			"subject_field_name" => "device_product_id",
		));
	}

	function getDevices() {
		return $this->getDevicesLister()->getRecords();
	}

	function toHumanReadableString(){
		$out = $this->getCatalogId();
		$out .= ", ".$this->toString();
		return $out;
	}

	function toString(){
		return $this->getFullName();
	}
}
