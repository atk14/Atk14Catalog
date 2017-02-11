<?php
class ProductsForm extends AdminForm {

	function set_up() {
		$this->add_field("catalog_id", new CatalogIdField(array(
			"label" => _("Katalogové číslo"),
		)));
		$this->add_translatable_field("name", new CharField(array(
			"label" => _("Název"),
		)));
		$this->add_translatable_field("shortinfo", new MarkdownField(array(
			"label" => _("Krátký popis"),
			"required" => false,
		)));
	}
}
