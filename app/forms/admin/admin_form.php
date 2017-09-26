<?php
class AdminForm extends ApplicationForm{

	/**
	 * Prida policka pro podporovane jazykove mutace.
	 *
	 * Parametry se pouzivaji stejne jako pro add_field.
	 * Uvnitr vola add_field pro kazdy podporovany jazyk.
	 *
	 *	$this->add_translatable_field(
	 *		"body",
	 *		new CharField(array("label" => "Tělo")),
	 *		array("required_langs" => "cs,en") // pokud nemaji byt vsechny jazyky povinne
	 *	);
	 *
	 * @param string $field_name identifikator formularoveho polcka
	 * @param Field $field
	 * @param array $options
	 */
	function add_translatable_field($field_name, $field, $options = array()) {
		global $ATK14_GLOBAL;

		$options += array(
			"required_langs" => $ATK14_GLOBAL->getDefaultLang(), // "_all_", "cs", "cs,en" nebo array("cs","en")
			"additional_langs" => array(), // dalsi jazyky, ktere aplikace jinak nema aktivovane
		);

		foreach(array("required_langs","additional_langs") as $k){
			if(!is_array($options[$k])){
				if($options[$k]==""){
					$options[$k] = array();
					continue;
				}
				$options[$k] = explode(",",$options[$k]); // "cs" -> array("cs"), "cs,en" => array("cs","en")
			}
		}

		$locales = $ATK14_GLOBAL->getConfig("locale");
		$langs = array_keys($locales);
		foreach($options["additional_langs"] as $al){
			if(!in_array($al,$langs)){ $langs[] = $al; }
		}
		$langs += $options["additional_langs"];

		# pro pripad, ze mame vicejazycna policka s '_id' na konci nazvu.
		# napr. display_image_cz_id, display_image_en_id
		$id_suffix = "";
		if (preg_match("/(.+)?(_(id)?)$/", $field_name, $matches)) {
			$field_name = $matches[1];
			$id_suffix = $matches[2];
		}

		# k zakladnim jazykum pridame dalsi
		$label = $field->label;
		if (!$label) $label = $field_name;
		$class = get_class($field);

		$required_langs = $options["required_langs"];
		if($required_langs==array("_all_")){
			$required_langs = $langs;
		}
		if(!$field->required){ $required_langs = array(); }

		foreach($langs as $lang){
			$w = clone($field->widget);
			$required = in_array($lang,$required_langs);
			if(!$required){
				unset($w->attrs["required"]);
			}
			$lang_field = new $class(array(
				"required" => $required,
				"label" => "$label [$lang]",
				"initial" => $field->initial,
				"help_text" => $field->help_text,
				"hint" => $field->hint,
				"disabled" => $field->disabled,
				"widget" => $w,
				"null_empty_output" => isset($field->null_empty_output)?$field->null_empty_output:false,
			));

			$this->add_field($field_name."_$lang".$id_suffix, $lang_field);
		}
	}

	function add_rank_field(){
		$this->add_field("rank",new RankField());
	}

	function add_slug_field(){
		$this->add_translatable_field("slug",new SlugField(array(
			"required" => false,
		)));
	}

	function add_code_field($options = array()){
		$options += array(
			"label" => _("Code"),
			"null_empty_output" => true,
			"max_length" => 255,
			"required" => false,
			"help_text" => _("An alternative key for system usage. Leave it unchanged if you are not sure.")
		);

		$this->add_field("code", new CharField($options));
	}

	function has_storno_button(){
		if(isset($this->has_storno_button)){ return $this->has_storno_button; }
		return false;
#		return $this->get_method()=="post";
	}
}
