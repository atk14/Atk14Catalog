<?php
/**
 *
 * @fixture cards
 * @fixture technical_specification_keys
 * @fixture technical_specifications
 */
class TcTechnicalSpecification extends TcBase {

	function test(){
		$ts = $this->technical_specifications["coffee__aroma"];

		$this->assertEquals("Strong",$ts->getContent());
		$this->assertEquals("Strong",$ts->getContent("en"));
		$this->assertEquals("Silná",$ts->getContent("cs"));
		
		$lang = "en";
		Atk14Locale::Initialize($lang);

		$this->assertEquals("Strong",$ts->getContent());
		$this->assertEquals("Strong","$ts");

		$lang = "cs";
		Atk14Locale::Initialize($lang);

		$this->assertEquals("Silná",$ts->getContent());
		$this->assertEquals("Silná","$ts");
	}
}
