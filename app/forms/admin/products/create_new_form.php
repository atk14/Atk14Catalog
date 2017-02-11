<?php
class CreateNewForm extends ProductsForm {

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
