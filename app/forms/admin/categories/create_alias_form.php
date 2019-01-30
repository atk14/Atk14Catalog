<?php
class CreateAliasForm extends CategoriesForm {

	function set_up() {
		$this->add_field("parent_category_id", new CategoryField(array(
			"label" => _("Umístit do kategorie"),
			"help_text" => _("Napište lomítko, pokud chcete prohledat strom kategorií od kořene."),
		)));
		$this->_add_fields();
	}

	function clean() {
		list($err,$d) = parent::clean();

		$current_c = $this->controller->category;
		$parent_c = $d["parent_category_id"];

		if ($current_c->hasNestedCategory($parent_c)) {
			$this->set_error("parent_category_id", "Nelze umístit alias do podřazené kategorie, dojde k zacyklení stromu");
		}
		return array($err,$d);
	}
}
