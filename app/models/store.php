<?php
class Store extends ApplicationModel Implements Rankable, Translatable, iSlug {

	static function GetTranslatableFields(){ return array("name","teaser","description","address","opening_hours"); }

	function setRank($rank){
		return $this->_setRank($static);
	}
}
