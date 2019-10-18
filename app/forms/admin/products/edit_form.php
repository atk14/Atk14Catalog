<?php
class EditForm extends ProductsForm {

	function clean() {
		list($err,$d) = parent::clean();

		$this->_clean_catalog_id($d);

		return array($err,$d);
	}
}
