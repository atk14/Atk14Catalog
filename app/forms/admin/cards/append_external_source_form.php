<?php
class AppendExternalSourceForm extends AdminForm {
	function set_up() {
		$dbmole = &PgMole::GetInstance();
		$sources_choices = $dbmole->selectIntoAssociativeArray("SELECT id, title||' - '||subtitle as title FROM external_sources ORDER BY id DESC");

		$this->add_field("external_source_id", new ChoiceField(array(
			"label" => "Zvol odkaz, který se bude zobrazovat u karty produktu",
			"choices" => $sources_choices,
			"widget" => new RadioSelect(),
		)));
	}

}
