<?php
class EditForm extends CardsForm {
	
	function set_up() {
		$this->_add_fields(array(
			"add_catalog_id_field" => !$this->controller->card->hasVariants(), // nema varianty -> menime catalog_id primo na karte
		));
		$this->add_slug_field();
	}

	function clean() {
		$d = $this->cleaned_data;
		list($err,$d) = parent::clean();

		if (isset($d["catalog_id"]) && (!isset($this->initial["catalog_id"]) || $d["catalog_id"]!=$this->initial["catalog_id"]) && ($p=Product::FindByCatalogId($d["catalog_id"]))) {
			$this->set_error("catalog_id", _("Zadané katalogové číslo je již použité"));
		}
		return array($err,$d);
	}
}
