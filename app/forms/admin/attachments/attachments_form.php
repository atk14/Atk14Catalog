<?php
class AttachmentsForm extends AdminForm{
	function add_name_field($options = array()){
		$options += array(
			"label" => _("Název"),
			"hint" => _("uživatelská příručka"),
			"max_length" => 255,
			"required" => false,
		);
		$this->add_translatable_field("name",new CharField($options));
	}
}
