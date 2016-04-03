<?php
class ImagesForm extends AdminForm{
	function set_up(){
		$this->add_translatable_field("name",new CharField(array(
			"label "=> _("Title"),
			"required" => false,
			"max_length" => 255,
		)));

		$this->add_translatable_field("description",new CharField(array(
			"label "=> _("Description"),
			"required" => false,
			"max_length" => 1000,
		)));
	}
}
