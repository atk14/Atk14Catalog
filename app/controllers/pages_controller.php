<?php
class PagesController extends ApplicationController {
	function detail() {
		$this->page_title = strip_tags($this->page->getTitle());
		$smarty = $this->_get_smarty();
		Atk14Require::Helper("modifier.markdown.php",$smarty);
		$this->page_description = strip_tags($this->page->getTeaser());

		$this->tpl_data["child_pages"] = $this->page->getChildPages();

		$breadcrumbs = new Navigation();
		$breadcrumbs->unshiftItem($this->page->getTitle());
		$page = $this->page;
		while($parent = $page->getParentPage()){
			$breadcrumbs->unshiftItem($parent->getTitle(),array("action" => "detail", "id" => $parent));
			$page = $parent;
		}
		$breadcrumbs->unshiftItem(ATK14_APPLICATION_NAME,array("action" => "main/index"));

		$this->tpl_data["breadcrumbs"] = $breadcrumbs;
	}

	function _before_filter(){
		$this->_find("page");
	}
}
