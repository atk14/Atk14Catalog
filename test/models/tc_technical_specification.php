<?php
/**
 * @fixture cards
 * @fixture technical_specification_keys
 */
class TcTechnicalSpecification extends TcBase {

	function test(){
		$ts = TechnicalSpecification::CreateNewRecord(array(
			"card_id" => $this->cards["coffee"],
			"technical_specification_key_id" => $this->technical_specification_keys["aroma"],
			"content" => "Strong",
			"content_localized_cs" => "Silná",
		));

		$this->assertEquals("Strong",$ts->getContent());
		$this->assertEquals("Strong",$ts->getContent("en"));
		$this->assertEquals("Silná",$ts->getContent("cs"));
		
		$lang = "en";
		Atk14Locale::Initialize($lang);

		$this->assertEquals("Strong",$ts->getContent());

		$lang = "cs";
		Atk14Locale::Initialize($lang);

		$this->assertEquals("Silná",$ts->getContent());
	}
}
