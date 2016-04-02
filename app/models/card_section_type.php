<?php
class CardSectionType extends ApplicationModel{
	const ID_INFORMATION = 6;
	const ID_VARIANTS = 1;

	function toString(){
		return $this->getName();
	}
}
