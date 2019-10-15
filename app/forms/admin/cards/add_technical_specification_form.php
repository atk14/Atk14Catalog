<?php
class AddTechnicalSpecificationForm extends AdminForm {

	function set_up(){
		$this->add_field("technical_specification_key_id", new TechnicalSpecificationKeyField(array(
			"label" => _("Specification name"),
		)));

		$this->add_field("content", new CharField(array(
			"label" => _("Value"),
		)));

		$this->set_button_text(_("Add new specification"));
	}
}
