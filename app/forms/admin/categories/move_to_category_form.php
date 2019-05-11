<?php
class MoveToCategoryForm extends AdminForm {

	function set_up() {
		$this->add_field("parent_category_id", new CategoryField(array(
			"label" => _("Parent category"),
			"treat_null_as_root" => true,
		)));

		$this->set_button_text(_("Move category"));
	}
}
