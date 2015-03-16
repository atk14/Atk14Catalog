<?php
class IndexForm extends CardsForm{
	function set_up(){
		$this->add_field("search",new SearchField(array(
			"lable" => _("Search"),
			"required" => false,
			"initial" => "TODO",
		)));
	}
}
