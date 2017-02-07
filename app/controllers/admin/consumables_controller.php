<?php
require_once(__DIR__ . "/related_cards_controller.php");
class ConsumablesController extends RelatedCardsController {

	function _before_filter(){
		parent::_before_filter();

		$this->lister = $this->card ? $this->card->getConsumablesLister() : null;
		$this->page_title_create_new = _("Adding new consumable product");
		$this->success_notice_create_new = _("Consumable product has been added");
	}
}
