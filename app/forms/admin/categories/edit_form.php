<?php
class EditForm extends CategoriesForm{
	function set_up(){
		$this->_add_name();
		$this->_add_slug();
	}
}
