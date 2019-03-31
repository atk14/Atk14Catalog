<?php
class BrandsRouter extends SluggishRouter{

	var $patterns = array(
		"en" => array("index" => "/brands/", "detail" => "/brands/<slug>/"),
		"cs" => array("index" => "/znacky/", "detail" => "/znacky/<slug>/"),
	);
}
