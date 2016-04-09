<?php
class BrandsController extends AdminController {

	function index() {
		$this->page_title = _("Listing brands");
		$this->tpl_data["brands"] = Brand::FindAll();
	}

	function create_new() {
		$this->page_title = _("New brand");
		$this->_save_return_uri();

		if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			$brand = Brand::CreateNewRecord($d);
			$this->flash->success("The brand was created");
			return $this->_redirect_to_action("edit", array("id" => $brand));
		}
	}

	function edit() {
		$this->page_title = _("Editing of the brand");
		$this->_save_return_uri();
		$this->form->set_initial($this->brand);

		if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			$this->brand->s($d,array("reconstruct_missing_slugs" => true));
			$this->flash->success(_("The brand was saved"));
			$this->_redirect_back();
		}
	}

	function destroy() {
		if (!$this->request->post()) { return $this->_execute_action("error404"); }
		if (!$this->brand->isDeletable()) { return $this->_execute_action("error403"); }
		$this->brand->destroy();
		if (!$this->request->xhr()) {
			$this->flash->success(_("The brand was deleted"));
			$this->_redirect_back();
		}
	}

	function set_rank() {
		if(!$this->request->post()){ return $this->_execute_action("error404"); }

		$this->render_template = false;
		$this->brand->setRank($this->params->getInt("rank"));
	}

	function _before_filter(){
		if(in_array($this->action,array("edit","destroy","set_rank"))){
			$this->_find("brand");
		}
	}
}
