<?php
class MoveToCategoryForm extends AdminForm {
	function set_up() {
		$this->add_field("parent_category_id", new CategoryField(array(
			"label" => _("Nadřazená kategorie"),
			"help_text" => _("Napište lomítko, pokud chcete prohledat strom kategorií od kořene."),
		)));
	}
}
