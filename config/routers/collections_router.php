<?php
class CollectionsRouter extends SluggishRouter{

	var $patterns = array(
		"en" => array("index" => "/collections/", "detail" => "/collections/<slug>/"),
		"cs" => array("index" => "/kolekce/", "detail" => "/kolekce/<slug>/"),
	);
}
