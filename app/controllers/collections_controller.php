<?php
class CollectionsController extends ApplicationController{
	function index(){
		$this->page_title = _("List of Collections");
		$this->tpl_data["collections"] = Collection::FindAll("visible",true);
		$this->breadcrumbs[] = _("Collections");
	}

	function detail(){
		$this->page_title = $this->collection->getName();
		$this->breadcrumbs[] = array(_("Collections"),$this->_link_to("index"));
		$this->breadcrumbs[] = $this->collection->getName();
	}

	function _before_filter(){
		if(in_array($this->action,array("detail"))){
			$this->_find("collection");
		}
	}
}
