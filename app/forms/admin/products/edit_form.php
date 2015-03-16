<?php
class EditForm extends AdminForm {
	function set_up() {
		$this->add_translatable_field("name", new CharField(array(
			"label" => _("Název"),
		)));
		$this->add_translatable_field("shortinfo", new WysiwygField(array(
			"label" => _("Krátký popis"),
			"required" => false,
		)));

		$this->add_field("override_navision_stockcount", new BooleanField(array(
			"label" => _("Přepsat skladovou zásobu"),
			"help_text" => _("Pokud příznak zaškrtnete, nebude se skladová zásoba aktualizovat z Navisionu, ale můžete si nastavit vlastní"),
		)));
		$this->add_field("stockcount", new IntegerField(array(
			"label" => _("Skladová zásoba"),
			"help_text" => _("Má význam pouze tehdy, zaškrtnete-li volbu 'Přepsat skladovou zásobu'"),
			"required" => false,
		)));
	}

	function clean() {
		$d = $this->cleaned_data;
		list($err,$d) = parent::clean();

		if ($d["override_navision_stockcount"]===true && !isset($d["stockcount"])) {
			$this->set_error("stockcount", _("Při volbě 'Přepsat skladovou zásobu' je nutné zadat skladovou zásobu"));
		}
		if ($d["override_navision_stockcount"]===false) {
			unset($d["stockcount"]);
		}
		return array($err,$d);
	}
}
