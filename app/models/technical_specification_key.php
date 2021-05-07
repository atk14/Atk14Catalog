<?php
class TechnicalSpecificationKey extends ApplicationModel implements Translatable {

	protected static $CacheKeys;

	use TraitGetInstanceByCode;

	public static function GetTranslatableFields(){ return array("key_localized"); }

	public static function GetInstanceByKey($key){
		if(!strlen($key)){ return null; }

		if(is_null(self::$CacheKeys)){
			$dbmole = self::GetDbmole();
			self::$CacheKeys = $dbmole->selectIntoAssociativeArray("SELECT key,id FROM technical_specification_keys");
		}

		if(isset(self::$CacheKeys[$key])){
			return Cache::Get("TechnicalSpecificationKey",self::$CacheKeys[$key]);
		}
		foreach(self::$CacheKeys as $k => $id){
			if(Translate::Lower($k)===Translate::Lower($key)){
				return Cache::Get("TechnicalSpecificationKey",$id);
			}
		}
	}

	/**
	 * $weight = TechnicalSpecificationKey::GetOrCreateKey("weight"); // nonlocalized key
	 * $weight = TechnicalSpecificationKey::GetOrCreateKey("Weight"); // the same as the previous one
	 */
	public static function GetOrCreateKey($key){
		if(!strlen($key)){ return null; }

		if($out = self::GetInstanceByKey($key)){
			return $out;
		}
		
		$out = self::CreateNewRecord(array("key" => $key));
		static::$CacheKeys = null;
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
