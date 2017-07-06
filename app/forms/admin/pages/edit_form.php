<?php
class EditForm extends StaticPagesForm {

	function set_up() {
		parent::set_up();
		$this->add_slug_field();
	}
}
