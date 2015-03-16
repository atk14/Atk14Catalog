<?php
class CardSectionTypeField extends ChoiceField{
	function __construct($options = array()){
		$choices = array("" => "-- "._("vyberte typ obsahu")." --");
		foreach(CardSectionType::FindAll(array("order_by" => "id")) as $ct){
			$choices[$ct->getId()] = $ct->getName();
		}
		$options["choices"] = $choices;
		parent::__construct($options);
	}
}
