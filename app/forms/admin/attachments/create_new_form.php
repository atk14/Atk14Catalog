<?php
class CreateNewForm extends AttachmentsForm{
	function set_up(){
		$this->add_name_field(array("required" => false));
		$this->add_field("attachment",new PupiqAttachmentField(array(
			"label" => _("Soubor"),
		)));
	}
}
