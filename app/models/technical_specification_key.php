<?php
class TechnicalSpecificationKey extends ApplicationModel implements Translatable {

	public static function GetTranslatableFields(){ return array("key_localized"); }

	public static function GetInstanceByKey($key){
		if(!strlen($key)){ return null; }

		($out = self::FindFirst("key=:key",array(":key" => $key))) ||
		($out = self::FindFirst("LOWER(key)=LOWER(:key)",array(":key" => $key)));

		return $out;
	}

	/**
	 * $weight = TechnicalSpecificationKey::GetOrCreateKey("weight"); // nonlocalized key
	 * $weight = TechnicalSpecificationKey::GetOrCreateKey("Weight"); // the same as the previous one
	 */
	public static function GetOrCreateKey($key){
		if(!strlen($key)){ return null; }

		($out = self::GetInstanceByKey($key)) ||
		($out = self::CreateNewRecord(array("key" => $key)));

		return $out;
	}

	function getKey($lang = null){
		global $ATK14_GLOBAL;

		if(is_null($lang)){
			$lang = $ATK14_GLOBAL->getLang();
		}

		if(strlen($key = $this->g("key_localized_$lang"))){
			return $key;
		}

		return $this->g("key");
	}

	function toString(){
		return (string)$this->getKey();
	}
}
