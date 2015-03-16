<?php
class Brand extends ApplicationModel implements Translatable, Rankable {

	static $automatically_sluggable = true;

	/**
	 * Translatable methods implementation.
	 */
	static function GetTranslatableFields() {
		return array("teaser","description");
	}

	function hasCards() {
		return !!$this->getCards(array("limit" => 1));
	}

	function isDeletable(){ return !$this->hasCards(); }

	function setRank($new_rank) { return $this->_setRank($new_rank); }

	/**
	 * Returns cards of this brand
	 */
	function getCards($options = array()) {
		$options += array(
			"conditions" => array(
				"brand_id" => $this,
				"visible" => true,
				"deleted" => false
			)
		);
		return Card::FindAll($options);
	}

	function toString() { return $this->getName(); }

}
