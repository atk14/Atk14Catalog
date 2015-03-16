<?php
class CreateNewForm extends AdminForm {
	function set_up() {
		$this->add_field("catalog_id", new CatalogIdField(array(
			"label" => _("Katalogové číslo"),
		)));
		$this->add_translatable_field("name", new CharField(array(
			"label" => _("Název"),
		)));
		$this->add_translatable_field("shortinfo", new WysiwygField(array(
			"label" => _("Krátký popis"),
			"required" => false,
		)));
	}

	function clean() {
		$d = &$this->cleaned_data;
		$product = null;
		isset($d["catalog_id"]) && ($product = Product::FindByCatalogId($d["catalog_id"]));
		if ($product && !$product->isDeleted()) {
			$this->set_error("catalog_id", _("Toto katalogové číslo je již použité"));
		}
		return array(null, $d);
	}
}
