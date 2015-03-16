<?php
class AddToCategoryForm extends AdminForm{
	var $has_storno_button = false;
	function set_up(){
		$this->set_action(Atk14Url::BuildLink(array(
			"action" => "add_to_category",
			"id" => $this->controller->card,
		)));
		$this->set_button_text(_("Zařadit do kategorie"));
		$this->add_field("category",new CategoryField(array(
			"label" => _("Kategorie"),
			"help_text" => _("Napište lomítko, pokud chcete prohledat strom kategorií od kořene."),
		)));
	}

	function clean() {
		list($e,$d) = parent::clean();

		if ($d["category"] && !$d["category"]->allowProducts()) {
			$this->set_error("category", _("Do této kategorie nelze vkládat produkty"));
		}
		return array($e,$d);
	}
}
