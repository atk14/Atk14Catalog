<?php
class TcCardSectionType extends TcBase {

	function test(){
		$cst = CardSectionType::CreateNewRecord(array(
			"code" => "testing",
		));

		$this->assertEquals("testing","$cst");

		$cst->s(array(
			"name_en" => "Testing",
			"name_cs" => "Testování",
		));

		$lang = "en";
		Atk14Locale::Initialize($lang);

		$this->assertEquals("Testing","$cst");

		$lang = "cs";
		Atk14Locale::Initialize($lang);

		$this->assertEquals("Testování","$cst");
	}
}
