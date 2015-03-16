<?php
class ImagesForm extends AdminForm{
	function set_up(){
		$this->add_translatable_field("name",new CharField(array(
			"label "=> _("Název"),
			"required" => false,
			"max_length" => 255,
		)));

		$this->add_translatable_field("description",new CharField(array(
			"label "=> _("Popis"),
			"required" => false,
			"max_length" => 1000,
		)));
	}
}
