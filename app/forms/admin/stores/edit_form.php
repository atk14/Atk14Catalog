<?php
class EditForm extends StoresForm {
	function set_up(){
		parent::set_up();
		$this->add_slug_field();
	}
}
