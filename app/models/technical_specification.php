<?php
class TechnicalSpecification extends ApplicationModel implements Translatable, Rankable {
	public static function GetTranslatableFields(){ return array("content_localized"); }

	function setRank($rank){
		return $this->_setRank($rank,array("card_id" => $this->g("card_id")));
	}

	function getKey(){
		return Cache::Get("TechnicalSpecificationKey",$this->getTechnicalSpecificationKeyId());
	}

	function getContentLocalized(){
		if(strlen($content = parent::getContentLocalized())){
			return $content;
		}
		return $this->getContent();
	}
}
