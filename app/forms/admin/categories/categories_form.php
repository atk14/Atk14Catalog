<?php
class CategoriesForm extends AdminForm{

	function _add_fields($options = array()){
		$options += array(
			"add_is_filter_field" => false,
			"add_slug_field" => false,
		);

		$this->add_translatable_field("name", new CharField(array(
			"label" => _("Name"),
		)));

		$options["add_is_filter_field"] && $this->add_field("is_filter",new BooleanField(array(
			"label" => _("Is this a filter?"),
			"help_text" => _("e.g. material or color"),
			"required" => false,
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

		$options["add_slug_field"] && $this->add_slug_field();
	}
}
