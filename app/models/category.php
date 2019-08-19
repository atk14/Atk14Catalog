<?php
class Category extends ApplicationModel implements Translatable, Rankable, iSlug {

	use TraitGetInstanceByCode;

	static function GetTranslatableFields() { return array("name","teaser","description"); }

	function setRank($new_rank){
		$this->_setRank($new_rank,array("parent_category_id" => $this->getParentCategoryId()));
	}

	static function CreateNewRecord($values, $options=array()) {
		$values += array(
			"parent_category_id" => null,
			"pointing_to_category_id" => null,
		);
		$parent = Category::FindById($values["parent_category_id"]);
		$target = Category::FindById($values["pointing_to_category_id"]);
		if ($target && $target->hasNestedCategory($parent)) {
			throw new NestedAliasException("Can not create alias to superior category");
		}
		return parent::CreateNewRecord($values,$options);
	}

	static function GetCategories($parent_category,$options = array()){
		$options += array(
			"use_cache" => true,
		);
		return Category::FindAll("parent_category_id",$parent_category,$options);
	}

	/**
	 * $jidelna = Category::GetInstanceByPath("mistnosti/jidelna");
	 */
	static function GetInstanceByPath($path,&$lang = null){
		$orig_lang = $lang;

		$path = (string)$path;

		if(!$path){ return null; }
		
		$parent_category_id = null;
		foreach(explode('/',$path) as $slug){
			if(!$c = Category::GetInstanceBySlug($slug,$lang,$parent_category_id)){
				$lang = $orig_lang; // nenechame nastaveny $lang na nejakou necekanou hodnotu
				return null;
			}
			if($pt = $c->getPointingToCategory()){
				$c = $pt;
			}
			$parent_category_id = $c->getId();
		}

		return $c;
	}

	static function GetInstanceByNamePath($path,&$lang = null){
		$orig_lang = $lang;

		$path = (string)$path;

		if(!$path){ return null; }
		
		$parent_category_id = null;
		foreach(explode('/',$path) as $slug){
			if(!$c = Category::GetInstanceByName($slug,$lang,$parent_category_id)){
				$lang = $orig_lang; // nenechame nastaveny $lang na nejakou necekanou hodnotu
				return null;
			}
			if($pt = $c->getPointingToCategory()){
				$c = $pt;
			}
			$parent_category_id = $c->getId();
		}

		return $c;
	}

	function getChildCategories($options = array()){
		$options += array(
			"direct_children_only" => true,
		);
		$children = Category::GetCategories($this,$options);

		if (!$options["direct_children_only"] && $children) {
			$other_children = array();
			foreach($children as $ch) {
				$ccc = $ch->getChildCategories($options);
				$children = array_merge($children,$ccc);
			}
		}
		return $children;
	}

	/**
	 * Pouze viditelne podkategorie.
	 */
	function getVisibleChildCategories(){
		return Category::FindAll("parent_category_id",$this,"visible",true,array("use_cache" => true));
	}

	/**
	 * Sousedici kategorie
	 */
	function getNeighbouringCategories($options = array()){
		$out = array();
		foreach(Category::GetCategories($this->getParentCategoryId(),$options) as $c){
			if($c->getId()==$this->getId()){ continue; }
			$out[] = $c;
		}
		return $out;
	}

	function hasChildCategories(){ return !!$this->getChildCategories(array("limit" => 1)); }

	function isFilter(){ return $this->getIsFilter(); }

	function isSubcategoryOfFilter(){
		$parent = $this->getParentCategory();
		return $parent && $parent->isFilter();
	}

	function isVisible(){
		$visible = $this->getVisible();
		if(!$visible){ return false; }
		$parent = $this->getParentCategory();
		if($parent){ return $parent->isVisible(); }
		return true;
	}

	function isPointingToCategory(){ return !is_null($this->getPointingToCategoryId()); }
	function isAlias(){ return $this->isPointingToCategory(); }

	function getPointingToCategory(){ return Category::GetInstanceById($this->getPointingToCategoryId()); }

	function getSlugSegment(){
		return (string)$this->getParentCategoryId();
	}

	function getSlugPattern($lang){ return $this->g("name_$lang"); }

	function getParentCategory(){ return Cache::Get("Category",$this->getParentCategoryId()); }

	function isDescendantOf($root_category){
		if($root_category->getId()==$this->getId()){ return true; }
		if($parent = $this->getParentCategory()){
			return $parent->isDescendantOf($root_category);
		}
		return false;
	}

	/**
	 * var_dump($category->getPathOfCategories()); // array($base,$parent,$category);
	 */
	function getPathOfCategories(){
		$out = array($this);
		$c = $this;
		while($p = $c->getParentCategory()){
			$out[] = $p;
			$c = $p;
		}
		return array_reverse($out);
	}

	function getRootCategory(){
		$out = $this;
		while($parent = $out->getParentCategory()){
			$out = $parent;
		}
		return $out;
	}

	function isRootCategory() {
		return is_null($this->getParentCategoryId());
	}

	function getAvailableFilters($options = array()){
		$options += array(
			"consider_child_categories" => true,
		);

		$filters = array();

		if($options["consider_child_categories"]){
			foreach($this->getChildCategories() as $c){
				if($c->isFilter()){ $filters[$c->getId()] = $c; }
			}
		}
		foreach($this->getNeighbouringCategories() as $c){
			if($c->isFilter()){ $filters[$c->getId()] = $c; }
		}
		if($p = $this->getParentCategory()){
			if($p->isFilter()){ $filters[$p->getId()] = $p; }
			$filters += $p->getAvailableFilters(array("consider_child_categories" => false));
		}
		return array_values($filters);
	}

	
	/**
	 * echo $jidelna->getPath(); // "mistnosti/jidelna"
	 */
	function getPath($lang = null){
		$slugs = array($this->getSlug($lang));
		$c = $this;
		while($p = $c->getParentCategory()){
			$slugs[] = $p->getSlug($lang);
			$c = $p;
		}
		$slugs = array_reverse($slugs);
		return join('/',$slugs);
	}

	function getNamePath($lang = null, $options=array()){
		$options += array(
			"glue" => "/",
		);
		$slugs = array($this->getName($lang));
		$c = $this;
		while($p = $c->getParentCategory()){
			$slugs[] = $p->getName($lang);
			$c = $p;
		}
		$slugs = array_reverse($slugs);
		return join($options["glue"],$slugs);
	}

	function getCardsLister(){
		return $this->getLister("Cards");
	}

	function getCardIds() {
		return $this->getCardsLister()->getRecordIds(["preread_data" => false]);
	}

	function getCards(){
		return $this->getCardsLister()->getRecords(["preread_data" => false]);
	}
	
	/**
	 * Adds a card into this category
	 *
	 * Actually it inserts the card at the beginning of the card list.
	 */
	function addCard($card){
		if($this->isFilter()){
			throw new Exception("Can't insert card $card into filter category $this");
		}
		if($this->isAlias()){
			throw new Exception("Can't insert card $card into alias category $this");
		}

		// Using CardsLister can consume very much memory in a large catalog
		//$lister = $this->getCardsLister();
		//if(!$lister->contains($card)) {
		//	return $lister->prepend($card);
		//}

		if(0===$this->dbmole->selectInt("SELECT COUNT(*) FROM category_cards WHERE category_id=:category_id AND card_id=:card_id",[":category_id" => $this, ":card_id" => $card])){
			$this->dbmole->insertIntoTable("category_cards",[
				"category_id" => $this,
				"card_id" => $card,
				"rank" => $this->dbmole->selectInt("SELECT COALESCE(MIN(rank)-1,0) FROM category_cards WHERE category_id=:category_id",[":category_id" => $this]),
			]);
		}
	}

	function getRecommendedCardsLister() {
		return $this->getLister("RecommendedCards", array("class_name" => "Card", "subject_field_name" => "card_id"));
	}

	function getRecommendedCards() {
		return $this->getRecommendedCardsLister()->getRecords();
	}

	function addRecommendedCard($card){
		if($this->isFilter()){
			throw new Exception("Can't insert card $card into filter category $this");
		}
		if($this->isAlias()){
			throw new Exception("Can't insert card $card into alias category $this");
		}
		$lister = $this->getRecommendedCardsLister();
		if(!$lister->contains($card)) {
			return $lister->append($card);
		}
	}

	function removeRecommendedCard($card) {
		return $this->getRecommendedCardsLister()->remove($card);
	}

	function isDeletable(){
		$parent_category_id = $this->getParentCategoryId();

		if(is_null($parent_category_id)){
			// koren -> pokud ma nejake deti, nelze smazat
			return !Category::FindFirst("parent_category_id",$this);
		}
		return !is_null($parent_category_id);
	}

	function getImage() {
		return $this->getImageUrl();
	}

	/**
	 * Can products be added to this category?
	 *
	 */
	function allowProducts() {
		return !$this->isFilter() && !$this->isAlias();
	}

	/**
	 * Can subcategories be added to this category?
	 * 
	 */
	function allowSubcategories() {
		return !$this->isSubcategoryOfFilter() && !$this->isAlias();
	}

	/**
	 * Can this category be moved somewhere else?
	 *
	 */
	function canBeMoved(){
		return !$this->isSubcategoryOfFilter();
	}

	/**
	 * Can a new category be created as an alias to this category?
	 *
	 */
	function canBeAliased(){
		return
			!$this->isFilter() &&
			!$this->isSubcategoryOfFilter() &&
			!$this->isAlias();
	}

	function destroy($destroy_for_real = null){
		foreach($this->getChildCategories() as $ch){
			$ch->destroy($destroy_for_real);
		}
		return parent::destroy($destroy_for_real);
	}

	function removeCard($card){
		return $this->getCardsLister()->remove($card);
	}

	/**
	 * Zjisti, jestli je zadana kategorie vnorena pod aktualni kategorii.
	 *
	 * Lze tak zjistit moznost zacykleni.
	 *
	 * @param Category $checked_category kategorie, u ktere overujeme vnoreni
	 *
	 * @return bool true - $checked_category je vnorena v aktualni kategorii; false - neni vnorena
	 */
	function hasNestedCategory($checked_category) {
		while (!is_null($checked_category)) {
			if ($checked_category->getId()==$this->getId()) {
				return true;
			}
			$checked_category = $checked_category->getParentCategory();
		}
		return false;
	}

	static function GetInstanceByName($name,&$lang = null,$segment = ''){
		$class_name = get_called_class();
		$o = new $class_name();
		$table_name = $o->getTableName();

		$record = Translation::FindFirst(array(
			"conditions" => array(
				"table_name" => $table_name,
				"key" => "name",
				"body" => $name,
				"lang" => $lang,
			),
		));
		return static::GetInstanceById($record->getRecordId());
	}

	/**
	 * Vrati idecka kategorii v cele vetvi, ktera zacina touto kategorii.
	 *
	 * $ids = $category->getBranchCategoryIds(); 
	 *
	 * Nejmensi vetev muze obsahovat pouze array($category->getId())
	 */
	function getBranchCategoryIds(){
		// $categories: ve kterych kategoriich hledame produktove karty
		// hledaji se vsechna id do nejposlednejsiho zanoreni
		$dbmole = Category::GetDbMole();
		$categories = array($this->getId());
		$_current_parents = $categories;
		while(1){
			$_categories = $dbmole->selectIntoArray("
				SELECT COALESCE(pointing_to_category_id,id) FROM categories WHERE
					parent_category_id IN :current_parents AND
					visible='t' AND
					is_filter='f'
			",array(":current_parents" => $_current_parents),"integer");
			if(!$_categories){ break; }
			# kontrola, ze se mezi vracenymi id nenachazi nejake, ktere uz mame z predchozich pruchodu
			# to by znamenalo zacykleni; proto ono id vyhodime
			foreach($_categories as $idx => &$item) {
				if (in_array($item, $categories)) {
					unset($_categories[$idx]);
				}
			}
			$_current_parents = $_categories;
			foreach($_categories as $_id){ $categories[] = $_id; }
		}
		return $categories;
	}

	function getBranchCategories(){
		return Cache::Get("Category",$this->getBranchCategoryIds());
	}

	function toString(){
		return (string)$this->getName();
	}
}

class NestedAliasException extends Exception {}
