<?php
class IndexForm extends CardsForm{

	function set_up(){
		$this->add_field("search",new SearchField([
			"lable" => _("Search"),
			"required" => false,
		]));
		$this->add_field("visible", new ChoiceField([
			"lable" => _("Visible"),
			"choices" => [
				"" => "-- "._("visibility")." --",
				"1" => _("only visible products"),
				"0" => _("only invisible products"),
			],
			"required" => false,
		]));
	}
}
