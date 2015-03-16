<?php
class CollectionsController extends ApplicationController{
	function index(){
		$this->page_title = _("List of Collections");
		$this->tpl_data["collections"] = Collection::FindAll("visible",true);
	}

	function detail(){
		$this->page_title = $this->collection->getName();
	}

	function _before_filter(){
		if(in_array($this->action,array("detail"))){
			$this->_find("collection");
		}
	}
}
