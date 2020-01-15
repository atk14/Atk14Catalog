<?php
class EditSlugsForm extends CardsForm {

	function set_up(){
		$this->add_translatable_field("name", new CharField(array(
			"label" => _("Name"),
			"disabled" => true,
		)));
		$this->add_slug_field();	
	}
}
