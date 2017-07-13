<?php
class TechnicalSpecification extends ApplicationModel implements Translatable, Rankable {
	public static function GetTranslatableFields(){ return array("content_localized"); }

	function setRank($rank){
		return $this->_setRank($rank,array("card_id" => $this->g("card_id")));
	}

	function getKey(){
		return Cache::Get("TechnicalSpecificationKey",$this->getTechnicalSpecificationKeyId());
	}

	function getContent(){
		global $ATK14_GLOBAL;
		$lang = $ATK14_GLOBAL->getLang();

		if(strlen($content = $this->g("content_localized_$lang"))){
			return $content;
		}

		return $this->g("content");
	}
}
