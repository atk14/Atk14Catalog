<?php
class ProductsForm extends AdminForm {

	function set_up() {
		$this->add_field("catalog_id", new CatalogIdField(array(
			"label" => _("Catalog number"),
		)));
		$this->add_translatable_field("label", new CharField(array(
			"label" => _("Variant name"),
			"hints" => array("XL","20ml","1kg","32GB"),
			"required" => true,
		)));
		$this->add_translatable_field("name", new CharField(array(
			"label" => _("Product name"),
			"required" => false,
			"help_text" => _("Fill in only when product name differs from it's card name"),
		)));
		$this->add_translatable_field("description", new MarkdownField(array(
			"label" => _("Description"),
			"required" => false,
		)));
	}
}
