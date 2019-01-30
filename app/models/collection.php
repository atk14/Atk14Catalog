<?php
class Collection extends ApplicationModel implements Translatable, Rankable, iSlug {

	static function GetTranslatableFields() {
		return array("name","teaser", "description");
	}

	function getSlugPattern($lang){ return $this->g("name_$lang"); }

	/**
	 * Najde kategorie, ve kterych jsou produkty od teto znacky.
	 */
	function getCategories() {
		$q = "SELECT distinct(category_cards.category_id)
			FROM collection_cards,category_cards
			WHERE
				collection_cards.card_id=category_cards.card_id AND collection_cards.collection_id=:collection_id";
		$categories = Category::GetInstanceById($this->dbmole->selectIntoArray($q, array(":collection_id" => $this)));
		return $categories;
	}

	function getCardsLister() {
		return $this->getLister("Cards");
	}

	function getCardIds($options=array()) {
		return $this->getCardsLister()->getRecordIds();
	}

	function getCards() {
		return $this->getCardsLister()->getRecords();
	}

	function addCard($card) {
		# produktova karta muze byt jen v jedne kolekci
		if ($card->getCollection()) {
			throw new SingleCollectionException("Product can not be placed in more than one collection");
		}
		$lister = $this->getCardsLister();
		if (!$lister->contains($card)) {
			$lister->append($card);
		}
	}

	function removeCard($card) {
		$this->getCardsLister()->remove($card);
	}

	function setRank($new_rank) { return $this->_setRank($new_rank); }

	function toString() { return $this->getName(); }

	function isDeletable(){
		return 0==$this->dbmole->selectInt("SELECT COUNT(*) FROM collection_cards WHERE collection_id=:this LIMIT 1",array(":this" => $this));
	}
}

class SingleCollectionException extends Exception {}

