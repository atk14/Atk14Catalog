<?php
class ArticlesRouter extends SluggishRouter{

	var $patterns = array(
		"en" => array("index" => "/articles/", "detail" => "/article/<slug>/"),
		"cs" => array("index" => "/clanky/", "detail" => "/clanky/<slug>/"),
	);
}
