<?php
class CreateNewForm extends AdminForm {
	function set_up() {
		$this->add_field("related_card_id", new CardField(array(
			"label" => "Související produkt",
		)));
	}
}
