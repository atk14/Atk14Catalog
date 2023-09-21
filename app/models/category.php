<?php
class Category extends ApplicationModel implements Translatable, Rankable, iSlug {

	use TraitGetInstanceByCode;

	static function GetTranslatableFields() { return array("name","long_name","teaser","description", "page_title", "page_description"); }

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

	function realMeId() { return $this->getPointingToCategoryId()?:$this->getId();}
	function realMe() { return $this->getPointingToCategoryId()?$this->getPointingToCategory():$this;}

	/**
	 * Vrati pole kategorii (jak jdou po ceste od korene)
	 * Pokud dana cesta neexistuje, vrati null
	 *
	 * ```
	 * $categories = Category::GetInstancesOnPath("mistnosti/jidelna/stul");
	 * $categories[0]->getSlug(); //mistnosti
	 * $categories[1]->getSlug(); //jidelna
	 * $categories[2]->getSlug(); //stul
	 * ```
	 */
	static function GetInstancesOnPath($path, &$lang = null, $start = null, $options = []) {
		$options += array(
			"dealias" => true,
		);

		$dealias = $options["dealias"];
		unset($options["dealias"]);

		$orig_lang = $lang;

		$path = (string)$path;
		if(!strlen($path)){ return []; }

		if(is_object($start)) {
			$parent_category_id = $start->getId();
		} else {
			$parent_category_id = $start;
		}
		$out = [];

		$cpath = '';
		foreach(explode("/",$path) as $slug){
			if(!$c = static::GetInstanceBySlug($slug,$lang,$parent_category_id,$options)){
				$lang = $orig_lang; // nenechame nastaveny $lang na nejakou necekanou hodnotu
				return null;
			}
			$dealias && ($c = $c->realMe());
			$cpath .= "/$slug";
			$out[] = $c;
			$parent_category_id = $c->getId();
		}
		return $out;
	}

	/**
	 * $jidelna = Category::GetInstanceByPath("mistnosti/jidelna");
	 */
	static function GetInstanceByPath($path,&$lang = null){
		$out = self::GetInstancesOnPath($path, $lang);
		if(!$out) { return null ; }
		return end($out);
	}

	/**
	 *
	 *	$catalog = Category::GetInstanceByName(null,"Catalog");
	 *	$shoes = Category::GetInstanceByName($catalog,"Shoes");
	 */
	static function GetInstanceByName($parent_category,$name,&$lang = null){
		global $ATK14_GLOBAL;

		$name = (string)$name;
		$categories = $parent_category ? $parent_category->getChildCategories() : Category::FindAll("parent_category_id",null);

		$langs = $lang ? [$lang] : $ATK14_GLOBAL->getSupportedLangs();
		foreach($categories as $category){
			foreach($langs as $l){
				if((string)$category->g("name_$l")===$name){
					$lang = $l;
					return $category;
				}
			}
		}
	}

	static function GetInstancesOnNamePath($path, &$lang = null){
		$orig_lang = $lang;

		$path = (string)$path;
		if(!$path){ return null; }

		$my_path='';
		$slugs = explode("/",$path);
		$parent_category = null;

		$out = array();

		$cpath = '';
		foreach(explode('/',$path) as $name){
			if(!$c = Category::GetInstanceByName($parent_category,$name,$lang)){
				$lang = $orig_lang; // nenechame nastaveny $lang na nejakou necekanou hodnotu
				return null;
			}
			$c = $c->realMe();
			$cpath .= "/$name";
			$out[] = $c;
			$parent_category = $c;
		}
		return $out;
	}

	static function GetInstanceByNamePath($path,&$lang = null){
		$out = self::GetInstancesOnNamePath($path, $lang);
		if(!$out) { return null ; }
		return end($out);
	}

	function getChildCategories($options = array()){
		$options += array(
			"direct_children_only" => true,
			"visible" => null,
		);
		$children = Category::GetCategories($this,$options);

		if (!$options["direct_children_only"] && $children) {
			$other_children = array();
			foreach($children as $ch) {
				$ccc = $ch->getChildCategories($options);
				$children = array_merge($children,$ccc);
			}
		}

		if(!is_null($options["visible"])){
			$visible = (bool)$options["visible"];
			$children = array_filter($children,function($child) use($visible){ return $child->isVisible() ^ !$visible; }); // XOR
		}

		$children = array_values($children);

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

	function getName($lang = null){
		return parent::getName($lang);
	}

	function getLongName($lang = null){
		$out = parent::getLongName($lang);
		if(strlen((string)$out)){ return $out; }
		return parent::getName($lang);
	}

	function getPageTitle($lang = null){
		$out = parent::getPageTitle($lang);
		if(strlen((string)$out)){ return $out; }
		return $this->getLongName($lang);
	}

	function getPageDescription($lang = null){
		$out = parent::getPageDescription($lang);
		if(strlen((string)$out)){ return $out; }
		$out = $this->getTeaser($lang);
		if(strlen((string)$out)){
			$out = Markdown($out);
			$out = String4::ToObject($out)->stripHtml()->toString();
			return $out;
		}
	}

	function isVisible($check_parent_visibility = true){
		$visible = $this->g("visible");
		if(!$visible){ return false; }
		if($check_parent_visibility){
			$parent = $this->getParentCategory();
			if($parent){ return $parent->isVisible(); }
		}
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
			"visible" => null,
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

		if(!is_null($options["visible"])){
			$visible = (bool)$options["visible"];
			$filters = array_filter($filters,function($filter) use($visible){ return $filter->isVisible() ^ !$visible; }); // XOR
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

	function getVisibleCards($options = []){
		$options += [
			"limit" => null,
		];
		$cards = array_filter($this->getCards(),function($card){ return $card->isVisible() && !$card->isDeleted(); });
		if($options["limit"]){
			$cards = array_slice($cards,0,$options["limit"]);
		}
		return array_values($cards);
	}
	
	/**
	 * Adds a card into this category
	 *
	 * Actually it inserts the card at the beginning of the card list.
	 */
	function addCard($card,$options = []){
		$options += [
			"first" => true,
		];

		if($this->isFilter()){
			throw new Exception("Can't insert card $card into filter category $this");
		}
		if($this->isAlias()){
			throw new Exception("Can't insert card $card into alias category $this");
		}

		// Using CardsLister can consume very much memory in a large catalog
		//$lister = $this->getCardsLister();
		//if(!$lister->contains($card)) {
		//	if($options["first"]) {
		//		return $lister->prepend($card);
		//	} else {
		//		return $lister->append($card);
		//	}
		//}

		if(0===$this->dbmole->selectInt("SELECT COUNT(*) FROM category_cards WHERE category_id=:category_id AND card_id=:card_id",[":category_id" => $this, ":card_id" => $card])){
			if($options["first"]){
				$MIN = "MIN";
				$delta = -1;
			}else{
				$MIN = "MAX";
				$delta = 1;
			}
			$this->dbmole->insertIntoTable("category_cards",[
				"category_id" => $this,
				"card_id" => $card,
				"rank" => $this->dbmole->selectInt("SELECT COALESCE($MIN(rank)+:delta,0) FROM category_cards WHERE category_id=:category_id",[":category_id" => $this,":delta" => $delta]),
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
