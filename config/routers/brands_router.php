<?php
class BrandsRouter extends SluggishRouter{
	var $patterns = array(
		"en" => "/brand/<slug>/",
		"cs" => "/znacka/<slug>",
	);
}
