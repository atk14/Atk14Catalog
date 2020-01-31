<?php
class TechnicalSpecificationsForm extends AdminForm {
	function set_up(){
		$this->add_field("technical_specification_key_id", new TechnicalSpecificationKeyField(array(
			"label" => _("Key"),
		)));

		$this->add_field("content", new CharField(array(
			"label" => _("Value"),
			"trim_value" => true,
		)));

		$this->add_translatable_field("content_localized", new CharField(array(
			"label" => _("Localized value"),
			"trim_value" => true,
			"required" => false,
		)));
	}
}
