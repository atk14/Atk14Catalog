<?php
/**
 * Z {link_to controller="pages" action="detail" id="4"}
 * udela
 * /zvitezili-jsme-v-soutezi-prodejna-roku-2012/
 */
class CardsRouter extends SluggishRouter{
	var $patterns = array(
		"en" => "/product/<slug>/",
		"cs" => "/produkt/<slug>/",
	);
}
