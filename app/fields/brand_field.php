<?php
class BrandField extends ChoiceField {
	function __construct($options=array()) {
		$choices = array("" => "-- "._("Značka")." --");
		foreach(Brand::FindAll(array("order_by" => Translation::BuildOrderSqlForTranslatableField("brands","name"))) as $_b) {
			$choices[$_b->getId()] = $_b->getName();
		}
		$options["choices"] = $choices;
		parent::__construct($options);
	}
}
