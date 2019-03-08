<?php
class CategoryRecommendedCardsController extends AdminController{
	// pridani doporucovaneho produktu do kategorie probiha taky v cards/add_to_category

	function create_new(){
		$this->page_title = _("Add recommended product to the category");

		$this->_save_return_uri();

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			$this->category->addRecommendedCard($d["card_id"]);

			$this->flash->success(_("Product has been added"));
			$this->_redirect_back();
		}
	}

	function set_rank(){
		$this->category->getRecommendedCardsLister()->setRecordRank($this->card,$this->params->getInt("rank"));

		$this->render_template = false;
	}

	function destroy(){
		$this->category->removeRecommendedCard($this->card);
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

		if(in_array($this->action,array("create_new")) && isset($this->category)){
			$this->_add_category_to_breadcrumbs($this->category);
		}
	}
}
