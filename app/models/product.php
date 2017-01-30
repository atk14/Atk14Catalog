<?php
class Product extends ApplicationModel implements Translatable,Rankable{
	
	static function GetTranslatableFields(){ return array("name", "shortinfo", "action_info"); }

	static function GetInstanceByCatalogId($catalog_id){
		($product = Product::FindByCatalogId($catalog_id,array("use_cache" => true))) ||
		($product = Product::FindFirst(array(
			"conditions" => "deleted='t' AND catalog_id LIKE '%-'||:catalog_id",
			"bind_ar" => array(":catalog_id" => $catalog_id),
			"use_cache" => true
		)));
		return $product;
	}

	function getName($lang = null){
		$card = $this->getCard();
		if(!$card->hasVariants()){ return $card->getName($lang); }
		return parent::getName($lang);
	}

	function getShortinfo($lang = null){
		$card = $this->getCard();
		if(!$card->hasVariants()){ return $card->getTeaser($lang); }
		return parent::getShortinfo($lang);
	}

	function getCard(){ return Cache::Get("Card",$this->getCardId()); }

	function getCatalogId(){
		$catalog_id = $this->g("catalog_id");
		if($this->isDeleted()){
			// deleted-444-123/456789 -> 123/456789
			$catalog_id = preg_replace('/^deleted-\d+-/','',$catalog_id);
		}
		return $catalog_id;
	}
	
	function getImages(){
		return ProductImage::GetImages($this);
	}

	function getImage(){
		if($images = $this->getImages()){
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
	function assemblyIsAvailable() {
		return $this->getAssembly()==true;
	}
	function assemblyCanBeOrdered() {
		return $this->assemblyIsAvailable();
	}

	function destroy($delete_for_real = false){
		if($delete_for_real){
			return parent::destroy();
		}

		if($this->isDeleted()){
			return null;
		}

		$this->s(array(
			"deleted" => true,
			"catalog_id" => sprintf("deleted-%s-%s",$this->getId(),$this->getCatalogId()),
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

	function isOnlyForIndividualTransport() {
		return $this->getIndividualTransport();
	}

	function isNewItem() {
		return $this->getNewItem()==true;
	}

	function getBannedShippings() {
		$types = preg_split("/[\s,]/", $this->g("banned_shipping"), -1, PREG_SPLIT_NO_EMPTY);
		return $types;
	}

	/**
	 * Produkt neni standardne na sklade. Lze objednat na zavolani
	 */
	function isAvailableOnRequest() {
		return $this->getAvailableOnRequest()==true;
	}

	function isClubItem() {
		return $this->getIsClubItem()==true;
	}

	/**
	 * TODO: asi by se to jeste mohlo otestovat na viditelnost.
	 */
	function canBeOrdered() {
		$c = $this->getCard();
		return (
			$this->isVisible() && !$this->isDeleted() && ($this->getStockcount()>0 || $this->isAvailableOnRequest()) &&
			$c->isVisible() && !$c->isDeleted()
		);
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
}
