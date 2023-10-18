<?php
class CardsController extends ApplicationController{

	function detail(){
		$card = $this->card;

		if($card->isDeleted() || !$card->isVisible()){
			// In case of a deleted or invisible product, the HTTP 404 Not Found status is set but the product is displayed on the page.
			$this->response->setStatusCode("404");
		}

		$this->page_title = $card->getPageTitle();
		$this->page_description = $card->getPageDescription();

		$this->tpl_data["products"] = $products = $card->getProducts();
		$this->tpl_data["categories"] = $card->getCategories(array("consider_invisible_categories" => false, "consider_filters" => false, "deduplicate" => true));

		$this->_add_card_to_breadcrumbs($card);
	}

	function _before_filter(){
		$this->_find("card");
	}
}
