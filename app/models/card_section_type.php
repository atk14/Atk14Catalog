<?php
class CardSectionType extends ApplicationModel{
	const ID_INFORMATION = 6;

	function toString(){
		return $this->getName();
	}
}
