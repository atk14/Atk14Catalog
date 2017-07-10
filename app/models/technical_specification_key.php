<?php
class TechnicalSpecificationKey extends ApplicationModel implements Translatable {
	public static function GetTranslatableFields(){ return array("name"); }

	/**
	 * $weight = TechnicalSpecificationKey::GetOrCreateKey("weight"); // nonlocalized key
	 * $weight = TechnicalSpecificationKey::GetOrCreateKey("Weight"); // the same as the previous one
	 */
	public static function GetOrCreateKey($key){
		if(!strlen($key)){ return null; }

		($out = self::FindFirst("key=:key",array(":key" => $key))) ||
		($out = self::FindFirst("LOWER(key)=LOWER(:key)",array(":key" => $key))) ||
		($out = self::CreateNewRecord(array("key" => $key)));

		return $out;
	}

	function getName(){
		if(strlen($name = parent::getName())){
			return $name;
		}
		return $this->getKey();
	}

	function toString(){
		return $this->getName();
	}
}
