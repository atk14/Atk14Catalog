<?php
class EditForm extends BrandsForm {
	function set_up(){
		parent::set_up();
		$this->add_slug_field();
	}
}
