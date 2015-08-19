<?php
class StaticPagesController extends AdminController {

	function index() {
		$this->page_title = _("Statické stránky");
		$this->tpl_data["root_static_pages"] = StaticPage::FindAll("parent_static_page_id",null);
	}

	function create_new() {
		$this->page_title = _("Vytvoření statické stránky");
		$this->request->get() && $this->form->set_initial($this->params);
		$this->_save_return_uri();
		if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			$static_page = StaticPage::CreateNewRecord($d);
			$this->flash->success(_("Stránka uložena"));
			$this->_redirect_to_action("edit", array("id" => $static_page));
		}
	}

	function edit() {
		$this->page_title = sprintf(_("Editace stránky '%s'"), $this->static_page->getTitle());
		$this->form->set_initial($this->static_page);
		if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			$this->static_page->s($d);
			$this->flash->success(_("Stránka uložena"));
			$this->_redirect_to("index");
		}

		// kvuli trideni podstranek
		$this->tpl_data["child_static_pages"] = $this->static_page->getChildStaticPages();
	}

	function set_rank(){
		if(!$this->request->post()){ return $this->_execute_action("error404"); }

		$this->render_template = false;
		$this->static_page->setRank($this->params->getInt("rank"));
	}

	function destroy() {
		if (!$this->request->post()) {
			return $this->_execute_action("error404");
		}
		$this->static_page->destroy();
		if (!$this->request->xhr()) {
			$this->flash->success(_("Stránka smazána"));
		}
	}

	function _before_filter() {
		if(in_array($this->action,array("edit","destroy","set_rank"))){
			$this->_find("static_page");
		}
	}
}
