<?php
class StoresController extends AdminController {

	function index(){
		$this->page_title = _("List of stores");
		$this->tpl_data["stores"] = Store::FindAll();
	}

	function create_new(){
		$this->_create_new(array(
			"page_title" => _("New store"),
		));
	}

	function edit(){
		$this->_edit(array(
			"page_title" => _("Edit store"),
		));
	}

	function destroy(){
		$this->_destroy();
	}

	function set_rank(){
		$this->_set_rank();
	}
}
