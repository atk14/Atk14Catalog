<?php
/**
 * Controller for Related Cards
 *
 * This is also a base class for ConsumablesController and AccessoriesController.
 */
class RelatedCardsController extends AdminController {

	function create_new() {
		$this->_add_card_to_breadcrumbs($this->card);
		$this->template_name = "application/create_new";
		$this->form = $this->_get_form("related_cards/create_new_form");
		$this->_save_return_uri();
		$this->page_title = $this->page_title_create_new;
		if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			if($this->card->getId()==$d["adding_card"]->getId()){
				$this->form->set_error("adding_card",_("The same product cannot be added to the product itself"));
				return;
			}
			$this->lister->remove($d["adding_card"]);
			$this->lister->add($d["adding_card"]);
			$this->flash->success($this->success_notice_create_new);
			return $this->_redirect_back();
		}
	}

	function remove() {
		if (!$this->request->post()) { return $this->_execute_action("error404"); }

		$this->lister->remove($this->params->getInt("id"));
		if (!$this->request->xhr()) {
			return $this->_redirect_back();
		}
		$this->template_name = "application/destroy";
	}

	function set_rank() {
		if (!$this->request->post()) { return $this->_execute_action("error404"); }

		$r = Card::FindById($this->params->getInt("id"));
		$this->lister->setRecordRank($r, $this->params->getInt("rank"));
		$this->render_template = false;
	}

	function _before_filter() {
		$card = $this->_find("card","card_id");

		if($card && $card->isDeleted()){
			$this->_execute_action("error404");
		}

		$this->lister = $this->card ? $this->card->getRelatedCardsLister() : null;
		$this->page_title_create_new = _("Adding new related product");
		$this->success_notice_create_new = _("Related product has been added");
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
