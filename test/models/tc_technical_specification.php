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

	function test_CreateForCard(){
		$book = $this->cards["book"];
		$peanuts = $this->cards["peanuts"];

		$this->assertEquals(null,$book->getTechnicalSpecification("pages"));
		$this->assertEquals(null,$book->getTechnicalSpecification("isbn"));
		//
		$this->assertEquals(null,$peanuts->getTechnicalSpecification("pages"));
		$this->assertEquals(null,$peanuts->getTechnicalSpecification("isbn"));

		TechnicalSpecification::CreateForCard($book,"pages","222");

		$this->assertEquals("222",$book->getTechnicalSpecification("pages"));
		$this->assertEquals(null,$book->getTechnicalSpecification("isbn"));
		//
		$this->assertEquals(null,$peanuts->getTechnicalSpecification("pages"));
		$this->assertEquals(null,$peanuts->getTechnicalSpecification("isbn"));

		TechnicalSpecification::CreateForCard($book,"isbn","11-22-33-44");

		$this->assertEquals("222",(string)$book->getTechnicalSpecification("pages"));
		$this->assertEquals("11-22-33-44",(string)$book->getTechnicalSpecification("isbn"));
		//
		$this->assertEquals(null,$peanuts->getTechnicalSpecification("pages"));
		$this->assertEquals(null,$peanuts->getTechnicalSpecification("isbn"));
	}
}
