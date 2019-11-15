<?php
class Translation extends ApplicationModel{

	protected static $CACHE = array();

	/**
	 * $brand = Brand::GetInstanceById(1);
	 * print_r(Translation::GetObjectStrings($brand)); // array("name_cs" => "...", "name_en" => )
	 */
	static function GetObjectStrings($obj){
		$class_name = get_class($obj);
		$table_name = $obj->getTableName();
		$record_id = $obj->getId();
		$cache_key = "$table_name,$record_id";

		if(!isset(Translation::$CACHE[$cache_key])){
			// nacteme najednou translations stringy pro vsechny pomoci Cache nacachovane objekty
			$ids = Cache::CachedIds($class_name);
			$ids[] = $record_id;

			$_ids = array();
			foreach($ids as $id){
				$_c_key = "$table_name,$id";
				if(isset(Translation::$CACHE[$_c_key])){ continue; }
				Translation::$CACHE[$_c_key] = array();
				$_ids[] = $id;
			}
			$ids = $_ids;

			$dbmole = Translation::GetDbmole();

			foreach($dbmole->selectRows("SELECT record_id,key,lang,body FROM translations WHERE table_name=:table_name AND record_id IN :ids",array(":table_name" => $table_name, ":ids" => $ids)) as $row){
				$_c_key = "$table_name,$row[record_id]";
				$key = $row["key"];
				$lang = $row["lang"];
				Translation::$CACHE[$_c_key]["{$key}_$lang"] = $row["body"];
			}
		}

		return Translation::$CACHE[$cache_key];
	}

	static function SetObjectStrings($obj,$strings){
		$table_name = $obj->getTableName();
		$record_id = $obj->getId();

		$cache_key = "$table_name,$record_id";
		unset(Translation::$CACHE[$cache_key]);

		foreach($strings as $k => $value){
			preg_match('/(.*)_([a-z]{2})$/',$k,$matches);
			$key = $matches[1];
			$lang = $matches[2];
			$existing = Translation::FindFirst("table_name",$table_name,"record_id",$record_id,"key",$key,"lang",$lang);

			if($existing){
				if($existing->getBody()!=$value){ $existing->s("body",$value); }
			}else{
				if($value===""){ continue; } // neukladame nic, kdyz je treba zalozit zaznam s prazdnym retezcem; TODO: je to ok?
				Translation::CreateNewRecord(array(
					"table_name" => $table_name,
					"record_id" => $record_id,
					"key" => $key,
					"lang" => $lang,
					"body" => $value,
				));
			}
		}
	}

	static function DeleteObjectStrings($obj){
		$table_name = $obj->getTableName();
		$record_id = $obj->getId();
		$dbmole = Translation::GetDbmole();
		$dbmole->doQuery("DELETE FROM translations WHERE table_name=:table_name AND record_id=:record_id",array(
			":table_name" => $table_name,
			":record_id" => $record_id,
		));
		Translation::$CACHE = array();
	}

	/**
	 * Vrati sql pro razeni zaznamu v tabulce podle policka, ktere je urcene jako vicejazycne a je ulozene v tabulce translations.
	 *
	 * Typicky lze uzit napr. pro Atk14Sortable v controlleru pro parametr "order_by", kdy neexistuje policko v primarni tabulce.
	 *
	 * Napr. v kontrolleru BrandsController chceme zajistit razeni podle vicejazycneho policka name.
	 * Tabulka 'brands' ale policko name nema, misto toho je jako translatable ulozeno v tabulce translations.
	 *
	 * 	$this->sorting->add("name", array("order_by" => Translation::BuildOrderSqlForTranslatableField("brands", "name")));
	 *
	 *
	 */
	static function BuildOrderSqlForTranslatableField($table_name, $field_name) {
		global $ATK14_GLOBAL;
		$lang = $ATK14_GLOBAL->getLang();
		return "(SELECT body from translations where table_name='$table_name' and key='$field_name' and lang='$lang' and record_id=$table_name.id)";
	}

	/**
	 * Vytvori zakladni tvar conditions a bind_ar,ktere lze pouzit pro hledani zaznamu podle vicejazycnych poli, predat finderu apod.
	 *
	 * Zakladni trida TableRecord, pripadne ApplicationModel neumi hledat zaznamy podle poli, ktera jsou oznacena jako translatable,
	 * a hodnoty jsou ulozene v jine tabulce.
	 *
	 * Cili je treba nahradit sql typu select id from brands where upper(name) like upper(:name) slozitejsim dotazem
	 * typu
	 * SELECT brands.id FROM brands,translations where translations.record_id=brands.id and translations.table_name='brands' and translations.key='name' and upper(translations.body) like upper(:name) and lang='cs'
	 *
	 * Tato metoda vrati dve pole:
	 * - $conditions - neco jako array(
	 * "id IN (SELECT record_id FROM translations WHERE upper(translations.body) LIKE upper(:search) AND translations.key IN :search_fields AND translations.lang=:lang AND translations.table_name=:table_name_brands)",
	 * )
	 *
	 * - $bind_ar - neco jako
	 * array(3) {
	 * 	':lang' => "cs",
	 * 	':search_fields' => array (
	 * 		"name",
	 * 	 ),
	 * 	 ':table_name_brands' => "brands",
	 * }
	 *
	 * Do vraceneho pole bind_ar je treba doplnit klic :search a hodnotu, podle ktere hledame
	 *
	 * 	list($conditions,$bind_ar) = Translation::BuildConditionsForTranslatableFields("brands",array("name"));
	 * 	$bind_ar[':search'] = "Sojuz";
	 *
	 * 	$brand = Brand::FindAll(array("conditions" => $conditions, "bind_ar" => $bind_ar));
	 */
	static function BuildConditionsForTranslatableFields($table_name, $fields=array(),&$conditions=array(),&$bind_ar=array()) {
		global $ATK14_GLOBAL;

		$subconditions = array();
		$sub_conditions[] = "upper(translations.body) LIKE upper(:search)";
		if($fields) {
			$sub_conditions[] = "translations.key IN :search_fields";
			$bind_ar[":search_fields"] = $fields;
		}
		$sub_conditions[] = "translations.lang=:lang";
		$sub_conditions[] = "translations.table_name=:table_name_$table_name";
		$conditions[] = sprintf("id IN (SELECT record_id FROM translations WHERE %s)",
			join(" AND ", $sub_conditions)
		);
		$bind_ar[":lang"] = $ATK14_GLOBAL->getLang();

		$bind_ar[":table_name_$table_name"] = $table_name;
		return array($conditions, $bind_ar);
	}
}
