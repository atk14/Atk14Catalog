<?php
class ProductsController extends AdminController {

	function create_new() {
		$this->page_title = _("New product variant");
		$this->_save_return_uri();

		if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			$product = $this->card->createProduct($d);
			if(!$this->card->hasVariants()){
				$this->card->s("has_variants",true);
			}
			$this->flash->success(_("Variant has been created"));

			$this->_redirect_back();
			/*
			$this->_redirect_to(array(
				"action" => "edit",
				"id" => $product,
				"_return_uri_" => $this->_get_return_uri(),
			)); // */
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

	function _before_filter() {
		if (in_array($this->action, array("create_new"))) {
			$this->_find("card","card_id");
		}
		if (in_array($this->action, array("edit","destroy","set_rank"))) {
			$product = $this->_find("product");
			$this->card = $product ? $product->getCard() : null;
		}

		if(isset($this->card)){
			$this->_add_card_to_breadcrumbs($this->card);
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
