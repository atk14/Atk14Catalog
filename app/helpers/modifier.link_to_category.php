<?php
/**
 * Builds link to a category according to the given code
 *
 *	<a href="{"catalog"|link_to_category}">Catalog</a>
 */
function smarty_modifier_link_to_category($code){
	$category = Category::GetInstanceByCode($code);

	if(!$category){
		PRODUCTION && trigger_error("Unknown category code: $code", E_USER_WARNING);
		return Atk14Url::BuildLink(array("namespace" => "", "controller" => "main", "action" => "page_not_found", "category" => $code));
	}

	return Atk14Url::BuildLink(array("namespace" => "", "controller" => "categories", "action" => "detail", "path" => $category->getPath()));
}
