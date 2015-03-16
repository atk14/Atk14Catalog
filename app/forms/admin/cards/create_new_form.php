<?php
class CreateNewForm extends CardsForm {
	function set_up() {
		$this->_add_fields(array(
			"add_catalog_id_field" => true,
			"catalog_id_required" => false,
		));
	}

	function clean() {
		list($err,$d) = parent::clean();

		if (isset($d["catalog_id"]) && ($product = Product::FindByCatalogId($d["catalog_id"]))) {
			$this->set_error("catalog_id", _("Zadané katalogové číslo je již použité"));
		}
		return array($err,$d);
	}
}
