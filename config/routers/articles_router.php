<?php
class ArticlesRouter extends SluggishRouter{
	var $patterns = array(
		"en" => "/article/<slug>/",
		"cs" => "/clanek/<slug>/",
	);
}
