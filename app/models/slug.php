<?php
class Slug extends ApplicationModel{
	protected static $CACHE = array();

	 /**
	  *
	 	* Tato metoda musi byt! ApplicationModel ma totiz svuj jiny getSlug()
		* Parametry jsou tady pro dodrzeni kompatibility s ApplicationModel::getSlug(). Uf
		*/
	function getSlug($lang = null,$segment = ''){ return $this->g("slug"); }
	
	static function GetRecordIdBySlug($table_name,$slug,&$lang = null,$segment = ''){
		global $ATK14_GLOBAL;

		$table_name_sluggish = Slug::StringToSluggish($table_name);
		// matching the generic form
		if(preg_match("/$table_name_sluggish-([a-z]{2})-(\d+)$/",$slug,$matches)){
			if(!$lang || $lang==$matches[1]){
				$lang = $matches[1];
				return (int)$matches[2];
			}
		}

		Slug::_ReadCache($table_name,$segment);

		if($lang){
			if(isset(Slug::$CACHE["$table_name,$segment"]) && isset(Slug::$CACHE["$table_name,$segment"][$lang]) && is_int($id = array_search($slug,Slug::$CACHE["$table_name,$segment"][$lang]))){
				return $id;
			}
		}else{
			foreach(Slug::$CACHE["$table_name,$segment"] as $l => $ary){
				if(is_int($id = array_search($slug,$ary))){
					$lang = $l;
					return $id;
				}
			}
		}

		return;
	}

	/**
	 * 
	 */
	static function SetObjectSlug($obj,$slug,$lang = null,$segment = '',$options = array()){
		global $ATK14_GLOBAL;
		if(!$lang){ $lang = $ATK14_GLOBAL->getDefaultLang(); } // !! default language
		$options += array(
			"reconstruct_missing_slugs" => false,
		);

		$table_name = $obj->getTableName();
		$id = $obj->getId();

		if(self::_IsGenericSlug($slug,$obj,$lang)){ $slug = ""; } // pozor! toto je genericky slug, ten nebudeme ukladat

		if(!$slug && $options["reconstruct_missing_slugs"]){
			$slug = self::_BuildSlug($obj,$lang);
		}

		$cache_key = "$table_name,$segment";
		unset(Slug::$CACHE[$cache_key]);

		$values = array(
			"table_name" => $table_name,
			"record_id" => $id,
			"lang" => $lang,
			//"segment" => $segment,
		);
		$slug_obj = Slug::FindFirst(array("conditions" => $values)); // hledame bez segmentu - ten totiz muze byt zmenen po aktualizaci objektu ($object->setValues())

		$values["segment"] = $segment;

		if(!$slug){
			$slug_obj && $slug_obj->destroy(); // mazeme
			return;
		}

		if($slug_obj){
			// aktualizujeme pouze, pokud se neco skutecne meni...
			if($slug!==$slug_obj->g("slug") || $segment!==$slug_obj->g("segment")){
				$slug_obj->s(array(
					"slug" => $slug,
					"segment" => $segment,
				));
			}
			return;
		}

		$values["slug"] = $slug;
		Slug::CreateNewRecord($values);
	}

	static function GetObjectSlug($obj,$lang = null,$segment = ''){
		global $ATK14_GLOBAL;
		if(!$lang){ $lang = $ATK14_GLOBAL->getLang(); } // !! current language

		$table_name = $obj->getTableName();
		$id = $obj->getId();

		$cache_key = "$table_name,$segment";
		Slug::_ReadCache($table_name,$segment);

		if(isset(Slug::$CACHE["$table_name,$segment"][$lang][$id])){
			return Slug::$CACHE["$table_name,$segment"][$lang][$id];
		}

		$table_name_sluggish = Slug::StringToSluggish($table_name);
		return "$table_name_sluggish-$lang-$id";
	}

	static function _ReadCache($table_name,$segment = '',$force_read = false){
		global $ATK14_GLOBAL;

		if(!$force_read && isset(Slug::$CACHE["$table_name,$segment"])){
			return;
		}
		
		Slug::$CACHE["$table_name,$segment"] = array();
		$CACHE = &Slug::$CACHE["$table_name,$segment"];
		foreach($ATK14_GLOBAL->getSupportedLangs() as $l){
			$CACHE[$l] = array();
		}

		$dbmole = Slug::GetDbmole();
		$rows = $dbmole->selectRows("SELECT lang,record_id,slug FROM slugs WHERE table_name=:table_name AND segment=:segment",array(":table_name" => $table_name,":segment" => $segment));
		foreach($rows as $row){
			$l = $row["lang"];
			$id = (int)$row["record_id"];
			$CACHE[$l][$id] = $row["slug"];
		}
	}

	static function DeleteObjectSlugs($obj){
		$table_name = $obj->getTableName();
		$record_id = $obj->getId();
		$dbmole = Slug::GetDbmole();
		$dbmole->doQuery("DELETE FROM slugs WHERE table_name=:table_name AND record_id=:record_id",array(
			":table_name" => $table_name,
			":record_id" => $record_id,
		));
		Slug::$CACHE = array(); // mazeme uplne vsechno...
	}

	static function StringToSluggish($string) {
		$_sluggish = new String4($string);
		return $_sluggish->toSlug();
	}

	/**
	 * Ulozi chybejici slugy daneho objektu.
	 */
	static function ComplementSlugs($object){
		$id = $object->getId();
		$table_name_sluggish = Slug::StringToSluggish($object->getTableName());
		$class_name = get_class($object);

		foreach($GLOBALS["ATK14_GLOBAL"]->getSupportedLangs() as $lang){
			$current_slug = $object->getSlug($lang);
			
			if($current_slug!="$table_name_sluggish-$lang-$id"){
				// nema genericky slug? - preskakujeme to
				continue;
			}

			$slug = self::_BuildSlug($object,$lang);
			if(!$slug){
				continue;
			}

			$object->setSlug($slug,$lang,$object->getSlugSegment());
		}
	}

	protected static function _BuildSlug($object,$lang){
		$class_name = get_class($object);

		if(!$pattern = $object->getSlugPattern($lang)){
			// ha, object sam nevi, podle ceho se ma slug vytvorit
			return;
		}

		$suffix = 0;
		$slug = Slug::StringToSluggish($pattern);
		while($class_name::GetInstanceBySlug($slug,$lang,$object->getSlugSegment())){
			$suffix++;
			$slug = Slug::StringToSluggish("$pattern $suffix");
		}
		return $slug;
	}

	protected static function _IsGenericSlug($slug,$object,$lang){
		$table_name = $object->getTableName();
		$id = $object->getId();

		$tbl_name_sluggish = Slug::StringToSluggish($table_name);
		return $slug=="$tbl_name_sluggish-$lang-$id";
	}
}
