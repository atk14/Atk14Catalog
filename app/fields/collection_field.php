<?php
class CollectionField extends ChoiceField {
	function __construct($options=array()) {
		$options += array(
			"required" => false,
		);
		$choices = array("" => "-- "._("Kolekce")." --");
		$conditions = $bind_ar = array();
		if (isset($options["collection_id"])) {
			$conditions[] = "id!=:collection_id";
			$bind_ar[":collection_id"] = $options["collection_id"];
		};

		foreach(Collection::FindAll(array(
			"conditions" => $conditions,
			"bind_ar" => $bind_ar,
			"order_by" => Translation::BuildOrderSqlForTranslatableField("collections","name")
		)) as $_b) {
			$choices[$_b->getId()] = $_b->getName();
		}
		$options["choices"] = $choices;
		parent::__construct($options);
	}

	function clean($value) {
		list($err, $value) = parent::clean($value);

		if (!is_null($err)) {
			return array($err,$value);
		}
		if (is_null($value)) {
			return array(null,null);
		}
		if (is_null($_sp = Collection::FindById($value))) {
			return array(_("Taková kolekce neexistuje"), null);
		}
		return array(null, $_sp);
	}
}
