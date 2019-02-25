<?php
class MoveToCategoryForm extends AdminForm {

	function set_up() {
		$this->add_field("parent_category_id", new CategoryField(array(
			"label" => _("Parent category"),
		)));

		$this->set_button_text(_("Move category"));
	}
}
