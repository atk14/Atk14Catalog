<?php
class Card extends ApplicationModel implements Translatable, iSlug {

	static function GetTranslatableFields(){ return array("name","teaser"); }

	static function CreateNewRecord($values,$options = array()){
		$collection_id = null;
		if (array_key_exists("collection_id", $values)) {
			$collection_id = $values["collection_id"];
			unset($values["collection_id"]);
		}
		$card = parent::CreateNewRecord($values,$options);
		return $card;
	}

	/**
	 * 
	 */
	static function GetProductsList($cards,$options = array()){
		static $STORE = array();

		$options += array(
			"force_read" => false,
		);

		if($options["force_read"]){ $STORE = array(); } // reset cache

		if(!$array_given = is_array($cards)){
			$cards = array($cards);
		}

		$ids = array();
		$ids_to_read = array();
		foreach($cards as $c){
			$id = is_object($c) ? $c->getId() : $c;
			if(!isset($STORE[$id])){
				$ids_to_read[] = $id;
				$STORE[$id] = array();
			}
			$ids[] = $id;
		}


		if($ids_to_read){
			$dbmole = Card::GetDbmole();
			$rows = $dbmole->selectRows("SELECT
						card_id,id,visible,deleted
					FROM
						products WHERE card_id IN :ids ORDER BY rank, id",array(":ids" => $ids_to_read));
			foreach($rows as $row){
				foreach($row as &$v){
					if(in_array($v,array('t','f'))){ $v = $v=='t'; continue; } // boolean
					if(is_numeric($v)){ $v = (int)$v; continue; } // integer
				}
				$id = $row["card_id"];
				unset($row["card_id"]);

				$STORE[$id][] = $row;
			}
		}

		$out = array();
		foreach($ids as $id){
			$out[$id] = $STORE[$id];
		}

		if(!$array_given){ $out = array_shift($out); }
		return $out;
	}

	function getSlugPattern($lang){
		return sprintf("%s-%d", $this->g("name_$lang"), $this->getId());
	}

	function getImages($options = array()){
		$images = Image::GetImages($this,$options);

		if(!$this->hasVariants()){ return $images; }

		foreach($this->getProducts() as $p){
			foreach($p->getImages() as $i){
				if($i->displayOnCard()){ $images[] = $i; }
			}
		}

		return $images;
	}

	function getAttachments(){
		return Attachment::GetAttachments($this);
	}

	/**
	 * Najde obrazky z variant produktu.
	 *
	 * Vynecha obrazky, ktere maji nastavenou vlastnost display_on_card na false
	 */
	function getProductImages($options = array()) {
		$images = array();
		foreach($this->getProducts() as $product) {
			$images = array_merge($images, $product->getImages());
		}
		$displayable_images = array();
		foreach($images as &$i) {
			if (!$i->displayOnCard()) {
				continue;
			}
			$displayable_images[] = $i;
		}
		return $displayable_images;
	}

	// Prvni obrazek
	function getImage(){
		if($ar = $this->getImages(array("limit" => 1))){
			return $ar[0];
		}
	}

	function hasVariants(){ return $this->getHasVariants(); }

	function createProduct($product_values) {
		$product_values["card_id"] = $this;
		$product = Product::CreateNewRecord($product_values);
		return $product;
	}

	/**
	 * $products = $card->getProducts();
	 */
	function getProducts($options = array()){
		return $this->_getProducts($options);
	}

	/**
	 * $product = $card->getFirstProduct();
	 */
	function getFirstProduct($options=array()){
		$options += array(
			"limit" => 1,
		);
		if($products = $this->_getProducts($options)){
			return $products[0];
		}
	}

	function toHumanReadableString(){
		return $this->getName();
	}

	protected function _getProducts($options = array()){
		$options += array(
			"deleted" => false, // false, true, null
			"visible" => true, // false, true, null

			"limit" => null,
			"order" => "rank",
		);

		$products_lists = Card::GetProductsList($this);

		$ids = array();
		foreach($products_lists as $item){
			if(!is_null($options["deleted"]) && $item["deleted"]!=$options["deleted"]){ continue; }
			if(!is_null($options["visible"]) && $item["visible"]!=$options["visible"]){ continue; }

			$ids[] = $item["id"];
		}

		return Product::GetInstanceById($ids,array("use_cache" => true));
	}


	function getCategoryLister() {
		return $this->getLister("category", array("table_name" => "category_cards"));
	}

	function addToCategory($category) {
		if(!is_object($category)){ $category = Category::GetInstanceById($category); }
		return $category->addCard($this);
	}

	function removeFromCategory($category){
		if(!is_object($category)){ $category = Category::GetInstanceById($category); }
		$category->removeCard($this);
	}

	function getCategoryIds() {
		return $this->getCategoryLister()->getRecordIds();
	}

	function getCategories() {
		return $this->getCategoryLister()->getRecords();
	}

	function getCardSections(){
		return CardSection::FindAll("card_id",$this,array(
			"order_by" => "rank, id",
			"use_cache" => true,
		));
	}

	/**
	 * Projde vsechny textove sekce a k nim prilozena vlozena videa.
	 * Vrati prvni instanci.
	 *
	 * @return EmbeddedVideo
	 */
	function getFirstEmbeddedVideoFromSections() {
		$embvid = null;
		foreach($this->getCardSections() as $cs) {
			if ($vids = $cs->getEmbeddedVideos()) {
				return array_shift($vids);
			}
		}
		return null;
	}

	function getRelatedCardsLister() {
		return $this->getLister("Card", array(
			"table_name" => "related_cards",
			"owner_field_name" => "card_id",
			"subject_field_name" => "related_card_id",
		));
	}

	function getRelatedCards() {
		return $this->getRelatedCardsLister()->getRecords();
	}

	function addRelatedCard($card) {
		return $this->_addRelatedCard($this->getRelatedCardsLister(),$card);
	}

	function removeRelatedCard($card) {
		return $this->_removeRelatedCard($this->getRelatedCardsLister(),$card);
	}

	function getConsumablesLister() {
		return $this->getLister("Card", array(
			"table_name" => "consumables",
			"owner_field_name" => "card_id",
			"subject_field_name" => "consumable_id",
		));
	}

	function getConsumables() {
		return $this->getConsumablesLister()->getRecords();
	}

	function addConsumable($card) {
		return $this->_addRelatedCard($this->getConsumablesLister(),$card);
	}

	function removeConsumable($card) {
		return $this->_removeRelatedCard($this->getConsumablesLister(),$card);
	}

	function getAccessoriesLister() {
		return $this->getLister("Card", array(
			"table_name" => "accessories",
			"owner_field_name" => "card_id",
			"subject_field_name" => "accessory_id",
		));
	}

	function getAccessories() {
		return $this->getAccessoriesLister()->getRecords();
	}

	function addAccessory($card) {
		return $this->_addRelatedCard($this->getAccessoriesLister(),$card);
	}

	function removeAccessory($card) {
		return $this->_removeRelatedCard($this->getAccessoriesLister(),$card);
	}

	protected function _addRelatedCard($lister,$card){
		if (!is_object($card)) {
			$card = Card::GetInstanceById($card);
		}
		if ($card->getId() === $this->getId()) {
			throw new SameCardException("Can't insert card $card into itself");
		}
		if (!$lister->contains($card)) {
			return $lister->append($card);
		}
	}

	protected function _removeRelatedCard($lister,$card){
		if (!is_object($card)) {
			$card = Card::GetInstanceById($card);
		}
		if ($lister->contains($card)) {
			return $lister->remove($card);
		}
	}

	function isDeleted(){ return $this->getDeleted(); }
	function isVisible(){ return $this->getVisible(); }

	function getTagsLister(){ return $this->getLister("Tags"); }
	function getTags(){ return Cache::Get("Tag",$this->getTagsLister()->getRecordIds()); }
	function setTags($tags){ return $this->getTagsLister()->setRecords($tags); }

	function getBrand(){
		return Cache::Get("Brand",$this->getBrandId());
	}

	function destroy($delete_for_real = false){
		if($delete_for_real){
			return parent::destroy();
		}

		if($this->isDeleted()){
			return null;
		}

		foreach($this->getProducts() as $p){
			$p->destroy();
		}

		foreach($this->getCategories() as $c){
			$c->removeCard($this);
		}

		Slug::DeleteObjectSlugs($this);

		$this->s(array(
			"deleted" => true,
		));
	}

	function getCollectionsLister() {
		return $this->getLister("Collections",array(
			"table_name" => "collection_cards",
		));
	}

	function getCollection() {
		return Collection::FindById($this->getCollectionId());
	}

	function getCollectionId() {
		if ($records = $this->getCollectionsLister()->getRecordIds()) {
			return $records[0];
		}
		return null;
	}

	function setCollection($collection) {
		$collection = Collection::FindById($collection);

		$lister = $this->getCollectionsLister();
		if ($_current_colection=$this->getCollection()) {
			$_current_colection->getCardsLister()->remove($this);
		}
		if (!is_null($collection)) {
			$lister->append($collection);
		}
	}

	function getExternalSourcesLister() {
		return $this->getLister("ExternalSources", array(
			"table_name" => "cards_external_sources",
		));
	}

	function getExternalSources() {
		return $this->getExternalSourcesLister()->getRecords();
	}

	function appendExternalSource($external_source) {
		$lister = $this->getExternalSourcesLister();
		if (!$lister->contains($external_source)) {
			return $this->getExternalSourcesLister()->append($external_source);
		}
		return false;
	}

	function removeExternalSource($external_source) {
		$this->getExternalSourcesLister()->remove($external_source);
	}

	/**
	 * Vrati dalsi karty ze stejne kolekce, jako je tato karta.
	 * Teda sadu bez teto karty.
	 */
	function getOtherCardsFromCollection() {
		if (!$_collection = $this->getCollection()) {
			return array();
		}
		$_cards = $_collection->getCards();
		$_cards2 = array();
		foreach($_cards as $idx => &$_c) {
			if ($_c->getId()==$this->getId()) {
				continue;
			}
			$_cards2[] = $_c;
		}
		return $_cards2;
	}

	/**
	 * Sourozenci z dane kategorie
	 */
	function getCategorySiblings($category) {
		$cards = $category->getCards();
		$_siblings = array();
		foreach($cards as $_c) {
			if ($_c->getId()==$this->getId()) {
				continue;
			}
			$_siblings[] = $_c;
		}
		return $_siblings;
	}

	function getAlternativeCards($options=array()) {
		$options = array_merge(array(
			"limit" => null,
		), $options);
		$cats = $this->getCategories();
		$alt_cards = $ids_taken = array();
		while($category = array_shift($cats)) {
			if (!$category->isVisible()) { continue; }
			if ($category->isFilter()) { continue; }

			foreach($this->getCategorySiblings($category) as $c){
				if(in_array($c->getId(),$ids_taken)){ continue; }

				$ids_taken[] = $c->getId();
				$alt_cards[] = $c;

				if(!is_null($options["limit"]) && sizeof($alt_cards)>=$options["limit"]){ break; }
			}

			if(!is_null($options["limit"]) && sizeof($alt_cards)>=$options["limit"]){ break; }
		}

		return $alt_cards;
	}

	function setValues($values,$options=array()) {
		if (array_key_exists("collection_id", $values)) {
			$collection_id = $values["collection_id"];
			unset($values["collection_id"]);
			$this->setCollection($collection_id);
		}
		parent::setValues($values,$options);
	}

	function toArray() {
		$ary = parent::toArray();
		$ary["collection_id"] = $this->getCollectionId();
		return $ary;
	}

	/**
	 * Ziska finder modelu Card pro zadanou kategorii
	 *
	 * Finder je pripraveny se vsemi podminkami.
	 * Bere v uvahu hodnoty vracene filtracnim formularem.
	 *
	 * @param array $filter_d filtracni parametry:
	 * - d - seznam id designeru (tab. designers)
	 * - b - seznam id znacek (tab. brands)
	 * - c - seznam id kolekci (tab. collections)
	 * - available - pouze produkty, ktere jsou na sklade
	 * - items_for_lefthanded - boolean - pokud true, vyhledavame jen products s left_handed_only='t'; jinak ignorujeme
	 *
	 * - f - seznam id filtru (tab. categories s priznakem is_filter='t')
	 * 	- ve tvaru f[0-9]+ => array(integer,integer, ...) ; hodnota u f je id kategorie s nazvem filtru, hodnota/y v poli je seznam hodnot filtru (id podrizenych  kategorii)
	 */
	static function GetFinderForCategory($category, $filter_d=array(), $options=array()) {

		$options = array_merge(array(
			"limit" => 50,
			"offset" => 0,
			"order" => null,
		),$options);

		# toto je jen priprava na moznost razeni vysledku filtru
		$order = "
				category_cards.category_id=:this_category DESC, -- napred karty zarazene primo v dane kategorii
				category_cards.rank,
				category_cards.id";

		$order_outer = "";
		if (strlen($options["order"])>0) {
			if ($options["order"]=="price_asc") {
				//$order_outer = "end_price ASC"; # -- od nejnizsi ceny";
			}
			if ($options["order"]=="price_desc") {
				//$order_outer = "end_price DESC"; # -- od nejvyssi ceny";
			}
		}

		$filter_d += array(
			"b" => array(), # brands
			"d" => array(), # designers
			"c" => null, 		# collection_cards
			# v katalogu musi byt videt jen dostupne produkty
		);

		$conditions = $bind_ar = array();
		$tables = array("category_cards","cards");
		
		$conditions[] = "category_cards.category_id IN :categories";
		$bind_ar[":categories"] = $category->getBranchCategoryIds();

		$conditions[] = "cards.id=category_cards.card_id";
		$conditions[] = "cards.visible='t'";
		$conditions[] = "cards.deleted='f'";

		# subquery qq vraci kartu vicekrat, pokud se nachazi ve vice kategoriich, s tim, ze karta zarazena v aktualni kategorii je na zacatku
		# select distinct(card_id) ale vyhodi duplicity v neurcitem poradi.
		#
		# proto pouzijeme tuto podminku, ktera vyhodi karty, ktere jsou jeste v jine nez aktualni kategorii (:this_category).
		# ta tam tedy zustane jen jednou a to na zacatku
		#
		$conditions[] = "(category_id!=:this_category AND category_cards.card_id IN (SELECT card_id FROM category_cards WHERE category_id=:this_category))=false";

		if($filter_d["d"]){
			$conditions[] = "cards.designer_id IN :designers";
			$bind_ar[":designers"] = $filter_d["d"];
		}

		if($filter_d["b"]){
			$conditions[] = "cards.brand_id IN :brands";
			$bind_ar[":brands"] = $filter_d["b"];
		}

		if($filter_d["c"]) {
			$tables[] = "collection_cards";
			$conditions[] = "collection_cards.card_id=cards.id";
			$conditions[] = "collection_cards.collection_id=:collection_id";
			$bind_ar[":collection_id"] = $filter_d["c"];
		}

		foreach($filter_d as $k => $val){
			if(preg_match('/^f/',$k) && $val && is_array($val)){ // TODO: Pozor!! Prosakuje zde parametr z URL: http://atk14catalog.localhost/children/books/?from=20
				$conditions[] = "category_cards.card_id IN (SELECT card_id FROM category_cards WHERE category_id IN :$k)";
				$bind_ar[":$k"] = $val;
			}
		}

		$bind_ar[":this_category"] = $category;

		if ($order_outer) {
			$order_outer = "ORDER BY $order_outer";
		}

		$query = "
			SELECT DISTINCT(card_id)
			FROM
				".join(",",$tables)."
			WHERE
				(".join(") AND\n(",$conditions).")
		";
				//-- ORDER BY $order
	
		/*
		echo $query;
		print_r($bind_ar);
		exit; */

		return Card::Finder(array(
			"query" => $query,
			"query_count" => "SELECT COUNT(*) FROM ($query)q",
			"bind_ar" => $bind_ar,
			"offset" => $options["offset"],
			"limit" => $options["limit"],
			"order_by" => null,
		));
	}
}

class SameCardException extends Exception {}

