<?php
class CatalogIdField extends RegexField {
	function __construct($options=array()) {

		$options = array_merge(array(
			"label" => _("Katalogové číslo"),
		), $options);
		parent::__construct('/^[0-9a-z]{4,}$/', $options);
		$this->update_messages(array(
			"invalid" => _("Toto nevypadá jako katalogové číslo"),
		));
	}
}
