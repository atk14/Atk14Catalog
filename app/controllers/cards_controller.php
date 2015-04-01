<?php
class CardsController extends ApplicationController{
	function detail(){
		if(!$card = $this->_find("card")){
			return $this->_execute_action("error404");
		}

		$this->page_title = $card->getName();
	}
}
