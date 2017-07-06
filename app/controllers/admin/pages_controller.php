<?php
class PagesController extends AdminController {

	function index() {
		$this->page_title = _("Pages");
		$this->tpl_data["root_pages"] = Page::FindAll("parent_page_id",null);
	}

	function create_new() {
		$this->page_title = _("Create page");
		$this->request->get() && $this->form->set_initial($this->params);
		$this->_save_return_uri();
		if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			$page = Page::CreateNewRecord($d);
			$this->flash->success(_("The page has been created successfuly"));
			$this->_redirect_to_action("edit", array("id" => $page));
		}
	}

	function edit() {
		$this->page_title = sprintf(_("Editing page '%s'"), strip_tags($this->page->getTitle()));
		$this->form->set_initial($this->page);
		if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			$this->page->s($d,array("reconstruct_missing_slugs" => true));
			$this->flash->success(_("Changes have been saved"));
			$this->_redirect_to("index");
		}

		// kvuli trideni podstranek
		$this->tpl_data["child_pages"] = $this->page->getChildPages();
	}

	function set_rank(){
		if(!$this->request->post()){ return $this->_execute_action("error404"); }

		$this->render_template = false;
		$this->page->setRank($this->params->getInt("rank"));
	}

	function destroy() {
		if (!$this->request->post()) {
			return $this->_execute_action("error404");
		}
		$this->page->destroy();
		if (!$this->request->xhr()) {
			$this->flash->success(_("The pages has been deleted"));
		}
	}

	function _before_filter() {
		if(in_array($this->action,array("edit","destroy","set_rank"))){
			$this->_find("page");
		}
	}
}
