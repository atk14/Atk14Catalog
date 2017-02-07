<?php
require_once(__DIR__ . "/related_cards_controller.php");
class AccessoriesController extends RelatedCardsController {

	function _before_filter(){
		parent::_before_filter();

		$this->lister = $this->card ? $this->card->getAccessoriesLister() : null;
		$this->page_title_create_new = _("Adding new accessory product");
		$this->success_notice_create_new = _("Accessory product has been added");
	}
}
