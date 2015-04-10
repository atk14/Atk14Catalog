<?php
class EditForm extends CollectionsForm {
	function set_up(){
		parent::set_up();
		$this->add_slug_field();
	}
}
