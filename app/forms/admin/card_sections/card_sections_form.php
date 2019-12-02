<?php
class CardSectionsForm extends AdminForm{

	function set_up(){
		$this->add_field("card_section_type_id",new CardSectionTypeField(array(
			"label" => _("Typ obsahu"),
			"initial" => CardSectionType::GetInstanceByCode("info"),
		)));
		$this->add_translatable_field("name",new CharField(array(
			"label" => _("Název"),
			"required" => false,
		)));
		$this->add_translatable_field("body",new MarkdownField(array(
			"label" => _("Text"),
			"required" => false,
		)));
	}
}
