<?php
class EditForm extends CardsForm {
	
	function set_up() {
		$this->_add_fields(array(
			"add_catalog_id_field" => !$this->controller->card->hasVariants(), // nema varianty -> menime catalog_id primo na karte
		));
		$this->add_slug_field();
	}

	function clean() {
		list($err,$d) = parent::clean();

		$this->_clean_catalog_id($d);

		return array($err,$d);
	}
}
