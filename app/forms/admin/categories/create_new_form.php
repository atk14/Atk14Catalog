<?php
class CreateNewForm extends CategoriesForm{
	function set_up(){
		$this->_add_name();
		$this->add_field("is_filter",new BooleanField(array(
			"label" => _("Bude to filtr?"),
			"help_text" => "Např. materiál, typ atd.",
			"required" => false,
		)));
	}
}
