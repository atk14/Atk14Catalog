<?php
/**
 * Builds link to a category according to the given code
 *
 *	<a href="{"catalog"|link_to_category}">Catalog</a>
 *	<a href="{"catalog"|link_to_category:"with_hostname"}">Catalog</a>
 */
function smarty_modifier_link_to_category($code,$options = ""){
	if(is_object($code)){
		$category = $code;
	}else{
		$category = Category::GetInstanceByCode($code);
	}

	$options = Atk14Utils::StringToOptions($options);

	if(!$category){
		PRODUCTION && trigger_error("Unknown category code: $code", E_USER_WARNING);
		return Atk14Url::BuildLink(array("namespace" => "", "controller" => "main", "action" => "page_not_found", "category" => $code),$options);
	}

	return Atk14Url::BuildLink(array("namespace" => "", "controller" => "categories", "action" => "detail", "path" => $category->getPath()),$options);
}
