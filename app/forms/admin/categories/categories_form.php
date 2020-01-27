<?php
class CategoriesForm extends AdminForm{

	function _add_fields($options = array()){
		$options += array(
			"add_is_filter_field" => false,
			"add_slug_field" => false,
			"add_page_title_and_description_fields" => false,
		);

		$this->add_translatable_field("name", new CharField(array(
			"label" => _("Name"),
		)));

		$this->add_translatable_field("long_name", new CharField(array(
			"label" => _("Long name"),
			"required" => false,
			"help_text" => _("Fill in if the name alone is not enough to describe the category.")
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

		if($options["add_page_title_and_description_fields"]){

			$this->add_translatable_field("page_title",new CharField(array(
				"label" => _("HTML title"),
				"required" => false,
				"max_length" => 255,
				"help_text" => h(_("Content for <html><head><title>. If left empty, the long name, resp. the name is used.")),
			)));

			$this->add_translatable_field("page_description", new CharField(array(
				"label" => _("HTML description"),
				"required" => false,
				"max_length" => 255,
				"help_text" => h(_('Content for <meta name="description">. If left empty, the teaser is used.')),
			)));
		}

		$this->add_field("image_url", new PupiqImageField(array(
			"label" => _("Image"),
			"required" => false,
			"help_text" => sprintf(_("Recommended image size is %dx%d"),1920,1080),
		)));

		$this->add_field("visible", new BooleanField(array(
			"label" => _("Is visible?"),
			"required" => false,
			"initial" => true,
		)));

		$options["add_slug_field"] && $this->add_slug_field();
	}
}
