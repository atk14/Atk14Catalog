<?php
class CategoryCardsController extends AdminController{
	// pridani produktu do kategorie probiha taky v cards/add_to_category

	function index() {
		$this->page_title = sprintf(_("Products in category %s"),strip_tags($this->category->getName()));
		$this->breadcrumbs[] = _("Product list");

		$this->tpl_data["finder"] = Card::Finder(array(
			"query" => "
				SELECT card_id FROM
					category_cards
				WHERE
					category_id=:category_id
				ORDER BY
					rank, id
			",
			"order_by" => "",
			"bind_ar" => array(
				":category_id" => $this->category
			),
			"offset" => $this->params->getInt("offset"),
			"limit" => 100,
			"use_cache" => true,
		));
	}

	function create_new(){
		$this->page_title = sprintf(_("Adding product into the category %s"),strip_tags($this->category->getName()));

		$this->_save_return_uri();

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			$this->category->addCard($d["card_id"]);

			$this->flash->success(_("The product was added"));
			$this->_redirect_back();
		}
	}

	function edit(){
		$this->page_title = sprintf(_("Edit ranking of %s"),$this->card->getName());

		$this->_save_return_uri();
		$this->request->get() && $this->form->set_initial($this->params);
		if($this->request->post() && ($d = $this->form->validate($this->params))){
			$this->category->getCardsLister()->setRecordRank($this->card,$d["rank"]-1);
			$this->_redirect_back();
		}
	}

	function set_rank(){
		if(!$this->request->post()){ return $this->_execute_action("error404"); }

		$this->category->getCardsLister()->setRecordRank($this->card,$this->params->getInt("rank"));

		$this->render_template = false;
	}

	function destroy(){
		if(!$this->request->post()){ return $this->_execute_action("error404"); }

		$this->category->removeCard($this->card);

		$this->template_name = "application/destroy";
	}

	function _before_filter(){
		if(in_array($this->action,array("set_rank","destroy","edit"))){
			$this->_find("category","category_id");
			$card_id_key = $this->params->defined("card_id") ? "card_id" : "id"; // "id" posila js udelatko pro trideni
			$this->_find("card",$card_id_key);
		}

		if(in_array($this->action,array("create_new","index"))){
			$this->_find("category","category_id");
		}

		if(in_array($this->action,array("index","create_new","edit")) && isset($this->category)){
			$this->_add_category_to_breadcrumbs($this->category);
		}
	}
}
