<?php
class CardSectionType extends ApplicationModel implements Translatable, Rankable{

	static function GetTranslatableFields(){ return array("name"); }

	function setRank($rank){
		$this->_setRank($rank);
	}

	function toString(){
		return (string)$this->getName();
	}
}
