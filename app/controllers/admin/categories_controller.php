<?php
class CategoriesController extends AdminController{
	function edit(){
		$this->page_title = sprintf(_('Editace kategorie "%s"'),strip_tags($this->category->getName()));
		$this->form->set_initial($this->category);

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			$this->category->s($d);

			$this->flash->success(_("Změny byly uloženy"));
			$this->_redirect_back();
		}
	}


	function move_to_category() {
		$this->page_title = _("Přesun kategorie");
		$this->form->set_initial("parent_category_id", $this->category->getParentCategory());
		$this->_save_return_uri();
			if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			$this->category->s("parent_category_id", $d["parent_category_id"]);
			$this->flash->success(_("Kategorie byla přesunuta"));
			return $this->_redirect_back();
		}
	}

	function set_rank(){
		if(!$this->request->post()){ return $this->_execute_action("error404"); }

		$this->category->setRank($this->params->getInt("rank"));
		$this->render_template = false;
	}

	function create_new(){
		$this->page_title = _("Nová podkategorie");

		$this->_save_return_uri();

		if($this->request->post() && ($d = $this->form->validate($this->params))){

			if($this->parent_category->isAlias()){
				$this->form->set_error(_("Nelze vytvořit novou kategorii, pokud je nadřazená kategorie alias."));
				return;
			}

			// pokud mame filtr, je mozne podkategorie zakladat do jedine urovne
			if(($super_p = $this->parent_category->getParentCategory()) && $super_p->isFilter()){
				$this->form->set_error(_("Na tomto místě už není možné založit novou kategorii"));
				return;
			}

			if($d["is_filter"]){
				if($this->parent_category->isFilter()){
					$this->form->set_error(_("Filtr nelze založit, pokud nadřazená kategorie je rovněž filtr."));
					return;
				}
			}
			
			$d["parent_category_id"] = $this->parent_category;

			$this->flash->success(_("Kategorie byla vytvořena"));
			$c = Category::CreateNewRecord($d);
			return $this->_redirect_to_action("edit", array("id" => $c));
		}
	}

	function destroy(){
		if(!$this->request->post() || !$this->category->isDeletable()){ return $this->_execute_action("error404") ;}

		$this->category->destroy();
		
		$this->flash->success(_("Kategorie byla smazána"));
		$this->_redirect_back();
	}

	function create_alias() {
		$this->page_title = _("Nový alias");
		$this->_save_return_uri();
		$this->form->set_initial($this->category);
		if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			$d["pointing_to_category_id"] = $this->category;
			$c = Category::CreateNewRecord($d);
			$this->flash->success(_("Alias vytvořen"));
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
		if($c->isFilter()){ $name = _("filtr").": $mame"; }
		if($c->isAlias()){ $name = _("alias").": $mame"; }
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
