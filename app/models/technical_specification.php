<?php
class TechnicalSpecification extends ApplicationModel implements Translatable, Rankable {
	public static function GetTranslatableFields(){ return array("content"); }

	function setRank($rank){
		return $this->_setRank($rank,array("card_id" => $this->g("card_id")));
	}

	function getKey(){
		return Cache::Get("TechnicalSpecificationKey",$this->getTechnicalSpecificationKeyId());
	}

	function getContent(){
		if(strlen($content = parent::getContent())){
			return $content;
		}
		return $this->getContentNotLocalized();
	}
}
