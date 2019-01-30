<?php
class CollectionsRouter extends SluggishRouter{
	var $patterns = array(
		"en" => "/collection/<slug>/",
		"cs" => "/kolekce/<slug>/",
	);
}
