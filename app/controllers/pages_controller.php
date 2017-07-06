<?php
class StaticPagesController extends ApplicationController {
	function detail() {
		$this->page_title = strip_tags($this->static_page->getTitle());
		$smarty = $this->_get_smarty();
		Atk14Require::Helper("modifier.markdown.php",$smarty);
		$this->page_description = strip_tags($this->static_page->getTeaser());

		$this->tpl_data["child_pages"] = $this->static_page->getChildStaticPages();

		$breadcrumbs = new Navigation();
		$breadcrumbs->unshiftItem($this->static_page->getTitle());
		$page = $this->static_page;
		while($parent = $page->getParentStaticPage()){
			$breadcrumbs->unshiftItem($parent->getTitle(),array("action" => "detail", "id" => $parent));
			$page = $parent;
		}
		$breadcrumbs->unshiftItem(ATK14_APPLICATION_NAME,array("action" => "main/index"));

		$this->tpl_data["breadcrumbs"] = $breadcrumbs;
	}

	function _before_filter(){
		$this->_find("static_page");
	}
}
