<?php
class AddToCategoryForm extends AdminForm{
	var $has_storno_button = false;
	function set_up(){
		$this->set_action(Atk14Url::BuildLink(array(
			"action" => "add_to_category",
			"id" => $this->controller->card,
		)));
		$this->set_button_text(_("Add to a category"));
		$this->add_field("category",new CategoryField(array(
			"label" => _("Category"),
			"help_text" => _("Type a slash if you want to search the tree of categories from the root."),
		)));
	}

	function clean() {
		list($e,$d) = parent::clean();

		if ($d["category"] && !$d["category"]->allowProducts()) {
			$this->set_error("category", _("You can not insert products into this category"));
		}
		return array($e,$d);
	}
}
