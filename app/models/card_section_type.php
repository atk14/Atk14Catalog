<?php
class CardSectionType extends ApplicationModel implements Translatable, Rankable{

	static function GetTranslatableFields(){ return array("name"); }

	function setRank($rank){
		$this->_setRank($rank);
	}

	function toString(){
		$name = strlen($this->getName()) ? $this->getName() : $this->getCode();
		return "$name";
	}
}
