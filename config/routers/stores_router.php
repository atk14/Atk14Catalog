<?php
class StoresRouter extends SluggishRouter{

	var $patterns = array(
		"en" => array("index" => "/stores/", "detail" => "/stores/<slug>/"),
		"cs" => array("index" => "/prodejny/", "detail" => "/prodejny/<slug>/"),
	);
}
