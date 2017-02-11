<?php
class CreateNewForm extends AdminForm {
	function set_up() {
		$this->add_field("adding_card", new CardField(array(
			"label" => _("Product")
		)));

		$this->set_button_text(_("Add product"));
	}
}
