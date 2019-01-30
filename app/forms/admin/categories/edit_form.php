<?php
class EditForm extends CategoriesForm{

	function set_up(){
		$this->_add_fields(array(
			"add_slug_field" => true,
		));
	}
}
