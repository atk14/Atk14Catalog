<?php
class TechnicalSpecificationsForm extends AdminForm {
	function set_up(){
		$this->add_field("technical_specification_key_id", new TechnicalSpecificationKeyField(array(
			"label" => _("Key"),
		)));

		$this->add_field("content", new TextField(array(
			"label" => _("Value"),
			"trim_value" => true,
		)));
	}
}
