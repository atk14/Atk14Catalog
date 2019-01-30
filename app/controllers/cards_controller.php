<?php
class CardsController extends ApplicationController{

	function detail(){
		if(!$card = $this->_find("card")){
			return $this->_execute_action("error404");
		}

		$this->page_title = $card->getName();
		$this->tpl_data["products"] = $product = $card->getProducts();

		$this->tpl_data["categories"] = $card->getCategories(array("consider_invisible_categories" => false, "consider_filters" => false));
	}
}
