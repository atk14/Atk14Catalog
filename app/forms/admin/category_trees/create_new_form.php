<?php
require_once(__DIR__."/../categories/categories_form.php");
class CreateNewForm extends CategoriesForm{

	function set_up(){
		$this->_add_fields(array(
			"add_is_filter_field" => true,
		));
	}
}
