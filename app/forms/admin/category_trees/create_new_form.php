<?php
require_once(__DIR__."/../categories/categories_form.php");
class CreateNewForm extends CategoriesForm{

	var $clean_code_automatically = false;

	function set_up(){
		$this->_add_fields(array(
			"add_is_filter_field" => false,
		));
	}

	function clean(){
		list($err,$d) = parent::clean();

		if($d && isset($d["code"]) && Category::FindByCode($d["code"])){
			$this->set_error("code",_("Stejný kód je již použit pro jiný záznam"));
		}

		return array($err,$d);
	}
}
