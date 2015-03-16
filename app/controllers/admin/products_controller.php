<?php
class ProductsController extends AdminController {
	function create_new() {
		$this->page_title = _("Nová varianta produktu");
		$this->_save_return_uri();

		if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			$product = $this->card->createProduct($d);
			$this->card->s("has_variants",true);
			$this->flash->success(_("Varianta vytvořena"));
			$this->_redirect_to_action("edit", array("id" => $product));
		}
	}

	function edit() {
		$variant = $this->product;
		$this->_save_return_uri();
		$this->page_title = sprintf(_("Úprava varianty produktu '%s - %s' ('%s')"), $variant->getCard()->getName(), $variant->getName(), $variant->getCatalogId());
		$this->form->set_initial($variant);
		if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			$variant->s($d);
			$this->flash->success(_("Varianta uložena"));
			$this->_redirect_back(array(
				"action" => "cards/edit",
				"id" => $this->card,
			));
		}
	}

	function destroy(){
		if(!$this->request->post()){
			return $this->_execute_action("error404");
		}
		$this->product->destroy();
	}

	function set_rank() {
		if (!$this->request->post()) {
			return $this->_execute_action("error404");
		}
		$this->render_template = false;
		$this->product->setRank($this->params->getInt("rank"));
	}

	function _setup_breadcrumbs_filter() {
		isset($this->card) && ($card = $this->card) || $card = null;
		(!$card && $this->product) && ($card = $this->product->getCard());

		$this->breadcrumbs->addItem(_("Produkty"), $this->_link_to("cards/index"));
		$this->breadcrumbs->addItem(sprintf(_("Karta produktu '%s' (id: %d)"), $card->getName(), $card->getId()), $this->_link_to(array("controller" => "cards", "action" => "edit", "id" => $card)));

		($this->action=="edit") && $this->breadcrumbs->addTextItem( sprintf(_("Varianta '%s'"), $this->product->getName()) );
		($this->action=="create_new") && $this->breadcrumbs->addTextItem( _("Nová varianta") );
	}

	function _before_filter() {
		if (in_array($this->action, array("create_new"))) {
			$this->_find("card","card_id");
		}
		if (in_array($this->action, array("edit","destroy","set_rank"))) {
			$this->_find("product");
			$this->card = $this->product->getCard();
		}
	}

	function _after_filter() {
		if (in_array($this->action, array("create_new","edit","destroy"))) {
			isset($this->product) && $this->product->getCard()->rebuildFulltext();
		}
	}

	function _redirect_back($default = null){
		if(is_null($default)){
			isset($this->card) && ($card_id = $this->card->getId());
			isset($this->product) && ($card_id = $this->product->getCardId());
			$default = array(
				"action" => "cards/edit",
				"id" => $card_id,
			);
		}
		return parent::_redirect_back($default);
	}
}
