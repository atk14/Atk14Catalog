<?php
class CollectionForm extends AdminForm {
	function set_up() {
		$this->add_translatable_field("name", new CharField(array(
			"label" => _("Name"),
		)));
		$this->add_translatable_field("teaser", new WysiwygField(array(
			"label" => _("Teaser"),
			"config" => "minimal",
			"required" => false,
		)));
		$this->add_translatable_field("description", new WysiwygField(array(
			"label" => _("Description"),
			"required" => false,
		)));
		$this->add_field("image_url", new PupiqImageField(array(
			"label" => _("Image"),
			"required" => false,
		)));
	}
}
