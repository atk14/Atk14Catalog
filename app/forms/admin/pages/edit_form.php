<?php
class EditForm extends PagesForm {

	function set_up() {
		parent::set_up();
		$this->add_slug_field();
	}
}
