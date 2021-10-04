<?php
require_once(__DIR__ . "/trait_slug_state_watcher.php");

class CategoriesController extends AdminController{

	use TraitSlugStateWatcher;

	function edit(){
		if($this->category->isAlias()){
			$title = _('Editing linking category "%s"');
		}elseif($this->category->isFilter()){
			$title = _('Editing filter category "%s"');
		}elseif($this->category->isSubcategoryOfFilter()){
			$title = _('Editing filter option "%s"');
		}else{
			$title = _('Editing category "%s"');
		}
		$this->page_title = sprintf($title,strip_tags($this->category->getName()));

		$this->_save_return_uri();
		$this->form->set_initial($this->category);

		if($this->request->post() && ($d = $this->form->validate($this->params))){

			$this->_save_slug_state($this->category);

			$this->category->s($d,array("reconstruct_missing_slugs" => true));

			$this->flash->success(_("Changes have been saved"));
			$this->_redirect_back_or_edit_slug();
		}

		$MAX_CARDS = 100;
		$count = $this->dbmole->selectInt("SELECT COUNT(*) FROM category_cards WHERE category_id=:category_id",[":category_id" => $this->category]);
		$this->tpl_data["cards_in_category"] = $count;
		$this->tpl_data["too_many_cards_in_category"] = $count>$MAX_CARDS;
	}

	function move_to_category() {
		$this->_walk([
			"get_category",
			"get_data",
			"fix_slug_collision",
			"move",
		]);
	}

	function move_to_category__get_category(){
		if(!$c = $this->_just_find("category")){
			$this->_execute_action("error404");
			return;
		}
		return $this->_next_step([
			"category" => $c,
			"return_uri" => $this->_get_return_uri(),
		]);
	}

	function move_to_category__get_data() {
		$category = $this->tpl_data["category"] = $this->returned_by["get_category"]["category"];

		if(!$category->canBeMoved()){
			return $this->_execute_action("error404");
		}

		$this->_prepare_breadcrumbs($category,true);
		$this->page_title = sprintf(_("Moving category %s"),strip_tags($category->getName()));
		$this->form->set_initial("parent_category_id", $category->getParentCategory());
		$this->_save_return_uri();
		if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			if($d["parent_category_id"] && $d["parent_category_id"]->getId()==$category->getId()){
				$this->form->set_error("parent_category_id",_("The category can not be moved under itself"));
				return;
			}
			return true;
		}
	}

	function move_to_category__fix_slug_collision(){
		global $ATK14_GLOBAL;

		$category = $this->tpl_data["category"] = $this->returned_by["get_category"]["category"];
		$parent_category = $this->parent_category = $this->tpl_data["parent_category"] = $this->form_data["get_data"]["parent_category_id"];

		$this->_prepare_breadcrumbs($category,true);
		$this->page_title = sprintf(_("Moving category %s"),strip_tags($category->getName()));

		$this->form->set_initial($category);

		$d = null;
		if(!$this->request->post()){
			$d = $this->form->get_initial();
			$this->form->detect_collision_slug($d,$parent_category);
			if(!$this->form->has_errors()){
				return $d;
			}
		}

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			return $d;
		}
	}

	function move_to_category__move(){
		$category = $this->returned_by["get_category"]["category"];
		$parent_category = $this->form_data["get_data"]["parent_category_id"];
		$values = $this->returned_by["fix_slug_collision"];
		$values["parent_category_id"] = $parent_category;
		$category->s($values);
		$this->flash->success(_("The category was moved"));
		$this->_redirect_to($this->returned_by["get_category"]["return_uri"]);
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
			$this->page_title .= " ("._("filter option").")";
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
		
		if($this->request->xhr()){
			$this->template_name = "application/destroy";
			return;
		}

		$this->flash->success(_("The category was deleted"));
		$this->_redirect_to("category_trees/index");
	}

	function create_alias() {
		if(!$this->category->canBeAliased()){
			return $this->_execute_action("error404");
		}

		$this->page_title = sprintf(_('Creating new link to the category "%s"'),$this->category->getName());
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

		if(!$add_self){
			$parent = $category->getParentCategory();
			$this->_add_category_to_breadcrumbs($parent);
			return;
		}

		$this->_add_category_to_breadcrumbs($category);
	}

	function _before_filter(){
		if(in_array($this->action,array("edit","add_card","set_rank","destroy","create_alias"))){
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
