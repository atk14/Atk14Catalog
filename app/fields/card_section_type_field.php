<?php
class CardSectionTypeField extends ChoiceField{

	function __construct($options = array()){
		$choices = array("" => "-- "._("select content type")." --");
		foreach(CardSectionType::FindAll() as $ct){
			$choices[$ct->getId()] = $ct->getName();
		}
		$options["choices"] = $choices;
		parent::__construct($options);
	}
}
