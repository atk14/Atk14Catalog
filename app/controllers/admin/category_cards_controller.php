<?php
class CategoryCardsController extends AdminController{
	// pridani produktu do kategorie probiha taky v cards/add_to_category

	function create_new(){
		$this->page_title = sprintf(_("Přidání produktu do kategorie %s"),strip_tags($this->category->getName()));

		$this->_save_return_uri();

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			$this->category->addCard($d["card_id"]);

			($ft = $d["card_id"]->getFulltext()) && $ft->s("flag_rebuild",true);
			$this->flash->success(_("Produkt byl přidán"));
			$this->_redirect_back();
		}
	}

	function set_rank(){
		$this->category->getCardsLister()->setRecordRank($this->card,$this->params->getInt("rank"));

		$this->render_template = false;
	}

	function destroy(){
		$this->category->removeCard($this->card);
	}

	function _before_filter(){
		if(in_array($this->action,array("set_rank","destroy"))){
			if(!$this->request->post()){ return $this->_execute_action("error404"); }
			$this->_find("category","category_id");
			$this->_find("card","id"); // takto to posila js udelatko pro trideni
		}

		if($this->action=="create_new"){
			$this->_find("category","category_id");
		}
	}

	function _after_filter() {
		if ($this->request->post() && in_array($this->action, array("destroy"))) {
			$this->card && ($ft = $this->card->getFulltext()) && $ft->s("flag_rebuild",true);
		}
	}

	function _setup_breadcrumbs_filter() {
		isset($this->category) && ($category = $this->category) || $category = null;

		$this->breadcrumbs->addItem(_("Seznam stromů"), $this->_link_to("category_trees/index"));
		$cats = array();
		$cats[] = $category;
		while($category = $category->getParentCategory()) {
			array_unshift($cats,$category);
		}
		isset($this->category) && ($category = $this->category) || $category = null;

		foreach($cats as $_c) {
			$this->breadcrumbs->addItem($_c->getName(), $this->_link_to(array("controller" => "cards", "action" => "edit", "id" => $_c)));
		}

		($this->action=="create_new") && $this->breadcrumbs->addTextItem( _("Přidat produkt") );
	}
}
