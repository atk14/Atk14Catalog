<?php
defined("CATALOG_ID_REGEXP") || define("CATALOG_ID_REGEXP",'/^[0-9A-Z_.\/-]{1,}$/i');
defined("CATALOG_ID_AUTO_UPPERIZE") || define("CATALOG_ID_AUTO_UPPERIZE",true);
defined("CATALOG_ID_AUTO_LOWERIZE") || define("CATALOG_ID_AUTO_LOWERIZE",false);
defined("CATALOG_ID_MAX_LENGTH") || define("CATALOG_ID_MAX_LENGTH",63);

/**
 * Field for entering a catalog number
 *
 * Catalog number is an *UNIQUE* product identifier.
 *
 * Regular pattern can be defined by the constant CATALOG_ID_REGEXP.
 */
class CatalogIdField extends RegexField {

	function __construct($options=array()) {
		$options = array_merge(array(
			"label" => _("Catalog number"),
			"max_length" => CATALOG_ID_MAX_LENGTH,
			"null_empty_output" => true,
		), $options);
		parent::__construct(CATALOG_ID_REGEXP, $options);
		$this->update_messages(array(
			"invalid" => _("This doesn't look like a catalog number"),
		));
	}

	function clean($value){
		if(CATALOG_ID_AUTO_UPPERIZE){
			$value = Translate::Upper($value);
		}
		if(CATALOG_ID_AUTO_LOWERIZE){
			$value = Translate::Lower($value);
		}

		return parent::clean($value);
	}
}
