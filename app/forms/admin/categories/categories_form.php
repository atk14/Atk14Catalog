<?php
class CategoriesForm extends AdminForm{

	function _add_name(){
		$this->add_translatable_field("name", new CharField(array(
			"label" => _("Název"),
		)));
		$this->add_translatable_field("description", new WysiwygField(array(
			"label" => _("Popis"),
			"required" => false,
			"config" => "category",
		)));
		$this->add_field("image_url", new PupiqImageField(array(
			"label" => _("Obrázek"),
			"required" => false,
		)));
	}

	function _add_slug(){
		$this->add_translatable_field("slug",new SlugField());
	}
}
