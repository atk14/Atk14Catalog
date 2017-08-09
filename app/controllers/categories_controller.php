<?php
class CategoriesController extends ApplicationController{
	function index(){
		$this->page_title = _("List of Categories");
		$this->tpl_data["categories"] = Category::FindAll("parent_category_id",null,"visible",true);
		$this->breadcrumbs[] = _("Categories");
	}

	function detail(){
		$path = $this->params->getString("path");

		if(!($category = Category::GetInstanceByPath($path)) || !$category->isVisible() || $category->isFilter() || (($p = $category->getParentCategory()) && $p->isFilter())){
			return $this->_execute_action("error404");
		}

		$this->breadcrumbs[] = array(_("Categories"),$this->_link_to("index"));

		$this->tpl_data["category"] = $category;
		$this->page_title = $category->getName();

		// v ceste k teto kategorii je najeky alias ->
		// vytiskneme do <head></head> element <link rel="canonical">
		if($path!=($_path = $category->getPath())){
			$this->tpl_data["canonical_path"] = $_path;
		}

		// parent categories
		$parent_categories = array();
		$slugs = explode("/",$path);
		array_pop($slugs);
		while($slugs){
			$parent_slug = join("/",$slugs);
			$pc = Category::GetInstanceByPath(join("/",$slugs));
			$parent_categories[] = array(
				"path" => $parent_slug,
				"name" => $pc->getName(),
			);
			array_pop($slugs);
		}
		$parent_categories = array_reverse($parent_categories);
		$this->tpl_data["parent_categories"] = $parent_categories;
		foreach($parent_categories as $pc){
			$this->breadcrumbs[] = array($pc["name"],$this->_link_to(array("path" => $pc["path"])));
		}
		$this->breadcrumbs[] = $category->getName();

		// child categories
		$child_categories = array();
		$filters = array();
		foreach($category->getChildCategories() as $cc){
			if(!$cc->isVisible()){ continue; }
			if($cc->isFilter()){ $filters[] = $cc; continue; }
			if ($cc->isAlias()) {
				$_card_finder = Card::GetFinderForCategory($cc->getPointingToCategory());
			} else {
				$_card_finder = Card::GetFinderForCategory($cc);
			}
			$child_categories[] = array(
				"path" => $path."/".$cc->getSlug(),
				"name" => $cc->getName(),
				"category" => $cc,
				"cards_count" => $_card_finder->getRecordsCount(),
			);
		}
		$this->tpl_data["child_categories"] = $child_categories;

		// TODO: compose relevant conditions
		$this->tpl_data["cards_finder"] = Card::GetFinderForCategory($category,array(),array("offset" => $this->params->getInt("offset")));
	}
}
