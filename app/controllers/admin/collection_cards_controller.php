<?php
class CollectionCardsController extends AdminController {

	function create_new() {
		$this->page_title = _('Adding a product into the collection');
		$this->_save_return_uri();
		if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			$this->collection->addCard($d["card_id"]);
			$this->flash->success("The product has been added to the collection");
			$this->_redirect_back();
		}
	}

	function remove() {
		if ($this->request->post()) {
			$this->collection->removeCard($this->card);
		}
	}

	function set_rank() {
		if(!$this->request->post()) {
			return $this->_execute_action("error404");
		}
		$this->render_template = false;
		$this->collection->getCardsLister()->setRecordRank($this->card,$this->params->getInt("rank"));
	}

	function _before_filter() {
		$this->_find("collection", "collection_id");
		in_array($this->action, array("remove","set_rank")) && $this->_find("card");
	}
}
