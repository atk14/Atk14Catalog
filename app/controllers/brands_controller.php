<?php
class BrandsController extends ApplicationController{

	function index(){
		$this->page_title = _("List of Brands");
		$brands = $this->tpl_data["brands"] = Brand::FindAll("visible",true);
		if(!$brands){
			$this->_execute_action("error404");
		}
		$this->head_tags->setCanonical($this->_build_canonical_url("brands/index"));
	}

	function detail(){
		$this->page_title = $this->brand->getName();
		$this->breadcrumbs[] = $this->brand->getName();
		$this->head_tags->setCanonical($this->_build_canonical_url(["action" => "brands/detail", "id" => $this->brand]));
	}

	function _before_filter(){
		$this->breadcrumbs[] = array(_("Brands"),$this->_link_to("index"));
		if(in_array($this->action,array("detail"))){
			$this->_find("brand");
		}
	}
}
