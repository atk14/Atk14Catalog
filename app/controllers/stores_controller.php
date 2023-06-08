<?php
class StoresController extends ApplicationController {

	function index(){
		$this->page_title = _("List of stores");
		$this->breadcrumbs[] = _("Stores");
		$this->tpl_data["stores"] = Store::FindAll();
		$this->head_tags->setCanonical($this->_build_canonical_url("stores/index"));
	}

	function detail(){
		$this->breadcrumbs[] = array(_("Stores"),"stores/index");
		$this->page_title = $this->breadcrumbs[] = $this->store->getName();
		$this->head_tags->setCanonical($this->_build_canonical_url(["action" => "stores/detail", "id" => $this->store]));
	}

	function _before_filter(){
		if($this->action=="detail"){
			$this->_find("store");
		}
	}
}
