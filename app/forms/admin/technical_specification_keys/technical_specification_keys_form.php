<?php
class TechnicalSpecificationKeysForm extends AdminForm {
	function set_up(){
		$this->add_field("key", new CharField(array(
			"label" => _("Key"),
			"max_length" => 255,
		)));

		$this->add_translatable_field("name", new CharField(array(
			"label" => _("Lokalizovaný název"),
			"required" => false,
		)));
	}
}
