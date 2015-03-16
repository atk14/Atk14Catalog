<?php
class CategoryTreesController extends AdminController{
	function index(){
		$this->page_title = _("Katalogové stromy");
		$this->tpl_data["roots"] = Category::GetCategories(null);
	}

	function create_new(){
		$this->page_title = _("Vytvořit nový katalogový strom");

		$this->_save_return_uri();
		if($this->request->post() && ($d = $this->form->validate($this->params))){
			$d["created_by_user_id"] = $this->logged_user;
			Category::CreateNewRecord($d);
			
			$this->flash->success(_("Katalogový strom byl vytvořen"));
			$this->_redirect_back();
		}
	}

	function detail(){
		$this->page_title = _("Katalogový strom");
	}

	function set_rank() {
		if(!$this->request->post()){ return $this->_execute_action("error404"); }

		$this->render_template = false;
		$this->root->setRank($this->params->getInt("rank"));
	}

	function destroy(){
		if(!$this->request->post() || !$this->root->isDeletable()){
			return $this->_execute_action("error404");
		}

		$this->root->destroy();
	}

	function _setup_breadcrumbs_filter() {
		if ($this->action=="index") {
			$this->breadcrumbs->addTextItem(_("Katalogové stromy"));
		} else {
			$this->breadcrumbs->addItem(_("Katalogové stromy"), $this->_link_to("category_trees/index"));
		}
	}

	function _before_filter(){
		if(in_array($this->action,array("detail","set_rank","destroy"))){
			$this->_find("root", array("class_name" => "Category"));
		}
	}
}
