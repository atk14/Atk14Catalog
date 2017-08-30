<?php
class PagesController extends ApplicationController {
	function detail() {
		$this->page_title = strip_tags($this->page->getTitle());
		$smarty = $this->_get_smarty();
		Atk14Require::Helper("modifier.markdown.php",$smarty);
		$this->page_description = strip_tags($this->page->getTeaser());

		$this->tpl_data["child_pages"] = $this->page->getChildPages();

		$pages = array($this->page);
		$p = $this->page;
		while($parent = $p->getParentPage()){
			$pages[] = $parent;
			$p = $parent;
		}
		$pages = array_reverse($pages);
		foreach($pages as $p){
			$this->breadcrumbs[] = array($p->getTitle(),$this->_link_to(array("action" => "detail", "id" => $p)));
		}
	}

	function _before_filter(){
		$this->_find("page");
	}
}
