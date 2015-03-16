<?php
class BrandsForm extends AdminForm {
	function set_up() {
		$this->add_field("name", new CharField(array(
			"label" => _("Název"),
		)));
		$this->add_translatable_field("teaser", new TextField(array(
			"label" => _("Teaser"),
			"required" => false,
			"help_text" => _("Brief description"),
		)));
		$this->add_translatable_field("description", new TextField(array(
			"label" => _("Info"),
			"required" => false,
		)));
		$this->add_field("url", new UrlField(array(
			"label" => _("Url"),
			"required" => false,
			"help_text" => _("Pages of the manufacturer"),
		)));
		$this->add_field("logo_url", new PupiqImageField(array(
			"label" => _("Logo"),
			"required" => false,
		)));
	}
}
