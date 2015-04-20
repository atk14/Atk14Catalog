<?php
class RelatedCardsController extends AdminController {

	function index() {
	}

	function create_new() {
		$this->_save_return_uri();
		$this->page_title = _("Nový související produkt");
		if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			$this->card->addRelatedCard($d["related_card_id"]);
			$this->flash->success("Související produkt přidán");
			return $this->_redirect_back();
		}
	}

	function remove() {
		if ($this->request->post()) {
			$this->card->removeRelatedCard($this->params->getInt("id"));
			if (!$this->request->xhr()) {
				return $this->_redirect_back();
			}
		}
	}

	function set_rank() {
		if ($this->request->post()) {
			$r = Card::FindById($this->params->getInt("id"));
			$this->card->getRelatedCardsList->setRecordRank($r, $this->params->getInt("rank"));
		}
	}

	function _before_filter() {
		$this->_find("card", array("key" => "card_id"));
		if (isset($this->card) && $this->card && $this->card->isDeleted()) {
			return $this->_redirect_back();
		}
	}

	function _redirect_back($default = null){
		if(is_null($default)){
			$default = array(
				"controller" => "cards",
				"action" => "edit",
				"id" => $this->card
			);
		}
		return parent::_redirect_back($default);
	}
}
