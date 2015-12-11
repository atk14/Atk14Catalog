<?php
class EditForm extends AdminForm {
	function set_up() {
		$this->add_translatable_field("name", new CharField(array(
			"label" => _("Název"),
		)));
		$this->add_translatable_field("shortinfo", new WysiwygField(array(
			"label" => _("Krátký popis"),
			"required" => false,
		)));
	}
}
