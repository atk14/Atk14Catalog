<?php
class TechnicalSpecificationKey extends ApplicationModel implements Translatable {
	public static function GetTranslatableFields(){ return array("name_localized"); }

	/**
	 * $weight = TechnicalSpecificationKey::GetOrCreateKey("weight"); // nonlocalized key
	 * $weight = TechnicalSpecificationKey::GetOrCreateKey("Weight"); // the same as the previous one
	 */
	public static function GetOrCreateKey($key){
		if(!strlen($key)){ return null; }

		($out = self::FindFirst("LOWER(name)=LOWER(:name)",array(":name" => $key))) ||
		($out = self::CreateNewRecord(array("name" => $key)));

		return $out;
	}

	function getName($localized = false){
		if($localized && !is_null($k = $this->getNameLocalized())){
			return $k;
		}
		return $this->g("name");
	}

	function toString(){
		return $this->getName(true);
	}
}
