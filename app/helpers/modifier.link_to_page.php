<?php
/**
 * Builds link to a page according to the given code
 *
 *	<a href="{"about_us"|link_to_page}">About us</a>
 */
function smarty_modifier_link_to_page($code){
	$page = Page::FindByCode($code);
	if(!$page){
		return "#";
	}

	return Atk14Url::BuildLink(array("namespace" => "", "controller" => "pages", "action" => "detail", "id" => $page));
}
