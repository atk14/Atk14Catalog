<?php
class NewsRouter extends SluggishRouter{
	var $url_sections_by_lang = array(
		"en" => "news",
		"cs" => "novinka",
	);
	var $model_class_name = "Article";
}
