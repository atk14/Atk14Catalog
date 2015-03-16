<?php
class CreateNewForm extends ImagesForm{
	function set_up(){
		$this->add_field("file",new PupiqImageField(array(
			"label" => _("Image"),
		)));
		parent::set_up();
	}
}
