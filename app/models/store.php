<?php
class Store extends ApplicationModel Implements Rankable, Translatable, iSlug {

	static function GetTranslatableFields(){ return array("name","teaser","description","address","opening_hours"); }

	function setRank($rank){
		return $this->_setRank($rank);
	}

	function getSlugPattern($lang){ return $this->g("name_$lang"); }

	function getGpsCoordinates(){
		if(is_null($this->g("location_lat")) || is_null($this->g("location_lng"))){
			return;
		}

		$lat = $this->g("location_lat")."N"; // TODO: zaporne je S
		$lng = $this->g("location_lng")."E"; // TODO: zaporne je W
		return "$lat, $lng";
	}
}
