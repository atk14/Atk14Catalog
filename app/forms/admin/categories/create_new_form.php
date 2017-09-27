<?php
class CreateNewForm extends CategoriesForm{
	function set_up(){
		$this->_add_name();
		$this->add_field("is_filter",new BooleanField(array(
			"label" => _("Should this be a filter?"),
			"help_text" => _("e.g. material or color"),
			"required" => false,
		)));
	}
}
