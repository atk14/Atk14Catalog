<?php
class CreateNewForm extends AdminForm{

	function set_up(){
		$this->add_field("card_id",new CardField(array(
			"label" => _("Product"),
		)));
	}
}
