<?php
class TechnicalSpecificationKeyField extends CharField {

	function __construct($options = array()){
		$options += array(
			"suggesting" => true,
			"widget" => new TextInput(),
			"max_length" => 255,
		);
		$options["trim_value"] = true;
		$options["null_empty_output"] = true;

		if($options["suggesting"]){
			$action = "technical_specification_keys";

			$options["widget"]->attrs["data-suggesting"] = "yes";
			$options["widget"]->attrs["data-suggesting_url"] = Atk14Url::BuildLink(array("namespace" => "api", "controller" => "suggestions", "action" => $action, "format" => "json"));
		}
		unset($options["suggesting"]);

		parent::__construct($options);
	}

	function format_initial_data($data){
		if(is_object($data)){
			return $data->getKey();
		}

		if(is_numeric($data) && ($_key = TechnicalSpecificationKey::FindById($data))){
			return $_key->getKey();
		}

		return (string)$data;
	}

	function clean($value){
		list($error,$value) = parent::clean($value);
		if(isset($error) || is_null($value)){ return array($error,$value); }

		$value = TechnicalSpecificationKey::GetOrCreateKey($value);

		return array($error,$value);
	}
}
