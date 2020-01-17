<?php
class EditForm extends SlugsForm {

	function set_up(){
		$this->add_slug_field();

		$this->set_button_text(_("Update slug"));
	}
}
