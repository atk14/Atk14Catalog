<?php
class CategoriesForm extends AdminForm{

	function _add_name(){
		$this->add_translatable_field("name", new CharField(array(
			"label" => _("Name"),
		)));
		$this->add_translatable_field("teaser", new MarkdownField(array(
			"label" => _("Teaser"),
			"required" => false,
			"help_text" => _("Brief description"),
		)));
		$this->add_translatable_field("description", new MarkdownField(array(
			"label" => _("Description"),
			"required" => false,
			"config" => "category",
		)));
		$this->add_field("image_url", new PupiqImageField(array(
			"label" => _("Image"),
			"required" => false,
		)));
		$this->add_field("visible", new BooleanField(array(
			"label" => _("Is visible?"),
			"required" => false,
			"initial" => true,
		)));
	}

	function _add_slug(){
		$this->add_slug_field();
	}
}
