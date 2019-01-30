<?php
class EditForm extends AdminForm {

	function set_up(){
		$this->add_field("rank",new IntegerField(array(
			"label" => _("Ranking"),
			"help_text" => _("The ranking starts with 1"),
		)));
	}
}
