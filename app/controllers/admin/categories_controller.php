<?php
class CategoriesController extends AdminController{

	function edit(){
		$this->page_title = sprintf(_('Editing category "%s"'),strip_tags($this->category->getName()));
		$this->form->set_initial($this->category);

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			$this->category->s($d,array("reconstruct_missing_slugs" => true));

			$this->flash->success(_("Changes have been saved"));
			$this->_redirect_back();
		}

		$MAX_CARDS = 100;
		$count = $this->dbmole->selectInt("SELECT COUNT(*) FROM category_cards WHERE category_id=:category_id",[":category_id" => $this->category]);
		$this->tpl_data["cards_in_category"] = $count;
		$this->tpl_data["too_many_cards_in_category"] = $count>$MAX_CARDS;
	}

	function move_to_category() {
		if(!$this->category->canBeMoved()){
			return $this->_execute_action("error404");
		}

		$this->page_title = _("Moving category");
		$this->form->set_initial("parent_category_id", $this->category->getParentCategory());
		$this->_save_return_uri();
			if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			$this->category->s("parent_category_id", $d["parent_category_id"]);
			$this->flash->success(_("The category was moved"));
			return $this->_redirect_back();
		}
	}

	function set_rank(){
		if(!$this->request->post()){ return $this->_execute_action("error404"); }

		$this->category->setRank($this->params->getInt("rank"));
		$this->render_template = false;
	}

	function create_new(){
		if(!$this->parent_category->allowSubcategories()){
			return $this->_execute_action("error404");
		}

		$this->page_title = _("New subcategory");

		$this->_save_return_uri();

		if($this->parent_category->isFilter()){
			unset($this->form->fields["is_filter"]);
		}

		if($this->request->post() && ($d = $this->form->validate($this->params))){

			if($this->parent_category->isAlias()){
				$this->form->set_error(_("Category cannot be created when the parent category is an alias"));
				return;
			}

			// pokud mame filtr, je mozne podkategorie zakladat do jedine urovne
			if(($super_p = $this->parent_category->getParentCategory()) && $super_p->isFilter()){
				$this->form->set_error(_("On this place a new category cannot be created"));
				return;
			}

			if($d["is_filter"]){
				if($this->parent_category->isFilter()){
					$this->form->set_error(_("Filter cannot be created when the parent category is also a filter"));
					return;
				}
			}
			
			$d["parent_category_id"] = $this->parent_category;

			$this->flash->success(_("Category has been created"));
			$c = Category::CreateNewRecord($d);
			return $this->_redirect_to(array(
				"action" => "edit",
				"id" => $c,
				"_return_uri_" => $this->_get_return_uri(),
			));
		}
	}

	function destroy(){
		if(!$this->request->post() || !$this->category->isDeletable()){ return $this->_execute_action("error404") ;}

		$this->category->destroy();
		
		$this->flash->success(_("The category was deleted"));
		$this->_redirect_back();
	}

	function create_alias() {
		if(!$this->category->canBeAliased()){
			return $this->_execute_action("error404");
		}

		$this->page_title = _("New alias");
		$this->_save_return_uri();
		$initial = $this->category->toArray();
		unset($initial["parent_category_id"]);
		$this->form->set_initial($initial);
		if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			$d["pointing_to_category_id"] = $this->category;
			$c = Category::CreateNewRecord($d);
			$this->flash->success(_("Alias was created"));
			$this->_redirect_to(array(
				"controller" => "categories",
				"action" => "edit",
				"id" => $c,
			));
		}
	}

	function _prepare_breadcrumbs($category,$add_self = false) {
		if(!$category){ return; }
		// breadcrumbs
		$ancestors = array();
		$c = $category;
		while($p = $c->getParentCategory()){
			$ancestors[] = $p;
			$c = $p;
		}
		$ancestors = array_reverse($ancestors);
		if($add_self){
			$ancestors[] = $category;
		}
		foreach($ancestors as $a){
			$this->breadcrumbs[] = array(
				$this->_get_category_name($a),
				$this->_link_to(array("action" => "edit", "id" => $a))
			);
		}
	}

	function _get_category_name($c){
		$name = $c->getName();
		if($c->isFilter()){ $name = _("filtr").": $name"; }
		if($c->isAlias()){ $name = _("alias").": $name"; }
		return $name;
	}

	function _before_filter(){
		if(in_array($this->action,array("edit","add_card","set_rank","destroy","move_to_category","create_alias"))){
			$c = $this->_find("category");
			$this->_prepare_breadcrumbs($c,$this->action!="edit");
		}

		if($this->action=="add_card"){
			$this->_find("card","card_id");
		}

		if($this->action=="create_new"){
			$c = $this->_find("parent_category",array(
				"class_name" => "Category",
				"key" => "parent_category_id",
			));
			$this->_prepare_breadcrumbs($c,true);
		}
	}

	function _redirect_back($default = null){
		if(!$default && isset($this->category)){
			$default = array(
				"controller" => "category_trees",
				"action" => "detail",
				"id" => $this->category->getRootCategory(),
			);
		}
		return parent::_redirect_back($default);
	}
}
