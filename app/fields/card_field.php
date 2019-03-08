<?php
class CardField extends ObjectField{

	function __construct($options = array()){
		parent::__construct($options);

		$this->update_messages(array(
			"not_found" => _("There is no such product card"),
		));
	}
}
