<?php
class BrandsController extends ApplicationController{
	function index(){
		$this->page_title = _("List of Brands");
		$this->tpl_data["brands"] = Brand::FindAll("visible",true);
	}

	function detail(){
		$this->page_title = $this->brand->getName();
	}

	function _before_filter(){
		if(in_array($this->action,array("detail"))){
			$this->_find("brand");
		}
	}
}
