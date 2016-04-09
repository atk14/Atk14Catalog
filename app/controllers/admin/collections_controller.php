<?php
class CollectionsController extends AdminController {

	function index(){
		$this->page_title = _("List of Collections");
		$this->tpl_data["collections"] = Collection::FindAll();
	}

	function create_new() {
		$this->page_title = _("New Collection");
		$this->_save_return_uri();

		if ($this->request->post() && ($d = $this->form->validate($this->params))) {
			$collection = Collection::CreateNewRecord($d);
			$this->flash->success(_("The collection has been saved.").'<br><a href="'.$this->_link_to(array("action" => "edit", "id" => $collection)).'">'._('Do you want to add some products to the collection?').'</a>');
			$this->_redirect_back();
		}
	}

	function edit() {
		$this->page_title = sprintf(_("Editing of the collection '%s'"), $this->collection->getName());
		$this->_save_return_uri();

		$this->form->set_initial($this->collection);
		if ($this->request->post() && ($d = $this->form->validate($this->params))) {
			$this->collection->setValues($d,array("reconstruct_missing_slugs" => true));
			$this->flash->success(_("Changes have been saved"));
			return $this->_redirect_back();
		}
	}

	function destroy() {
		if ($this->request->post()) {
			$this->collection->destroy();
			if (!$this->request->xhr()) {
				$this->flash->success(_("The collection has been deleted"));
				return $this->_redirect_back();
			}
		}
	}

	function _before_filter() {
		if(in_array($this->action,array("edit","destroy","add_product","remove_product"))){
			$this->_find("collection");
		}
	}
}
