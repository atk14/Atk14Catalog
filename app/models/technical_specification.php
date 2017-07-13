<?php
class TechnicalSpecification extends ApplicationModel implements Translatable, Rankable {
	public static function GetTranslatableFields(){ return array("content_localized"); }

	/**
	 * Saves a technical specification for the given product card
	 *
	 *	$ts = TechnicalSpecification::CreateForCard($card,"width","12.3 in");
	 *	$ts = TechnicalSpecification::CreateForCard($card,"width",array("content" => "12.3 in", "content_localized_cs" => "17cm");
	 */
	public static function CreateForCard($card,$key,$values){
		if(!is_a($key,"TechnicalSpecificationKey")){
			$key = TechnicalSpecificationKey::GetOrCreateKey($key);
		}
		if(!is_array($values)){
			$values = array("content" => $values);
		}
		$values["card_id"] = $card;
		$values["technical_specification_key_id"] = $key;
		return TechnicalSpecification::CreateNewRecord($values);
	}

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
