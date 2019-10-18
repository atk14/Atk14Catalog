<?php
class CreateNewForm extends CardsForm {

	function set_up() {
		$this->_add_fields(array(
			"add_catalog_id_field" => true,
			"catalog_id_required" => false,
			"add_information_fields" => true,
		));
	}

	function clean() {
		list($err,$d) = parent::clean();

		$this->_clean_catalog_id($d);

		return array($err,$d);
	}
}
