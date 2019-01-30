<?php
class Brand extends ApplicationModel implements Translatable, Rankable, iSlug {

	/**
	 * Translatable methods implementation.
	 */
	static function GetTranslatableFields() {
		return array("teaser","description");
	}

	function getSlugPattern($lang){ return $this->getName(); }

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

	function getImages($options = array()){
		return Image::GetImages($this,$options);
	}

	function getAttachments(){
		return Attachment::GetAttachments($this);
	}

	function toString() { return $this->getName(); }

	function destroy($destroy_for_real = null){
		$this->dbmole->doQuery("UPDATE cards SET brand_id=NULL WHERE brand_id=:id AND deleted='t'",array(":id" => $this));
		return parent::destroy($destroy_for_real);
	}
}
