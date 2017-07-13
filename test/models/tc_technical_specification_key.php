<?php
class TcTechnicalSpecificationKey extends TcBase {

	function test(){
		$this->assertNull(TechnicalSpecificationKey::FindByKey("weight")); // record doesn't exist

		$weight = TechnicalSpecificationKey::GetOrCreateKey("weight");
		$weight2 = TechnicalSpecificationKey::GetOrCreateKey("Weight");
		$height = TechnicalSpecificationKey::GetOrCreateKey("Height");

		$this->assertEquals("weight",$weight->getName());
		$this->assertEquals("weight",$weight2->getName());

		$this->assertEquals($weight->getId(),$weight2->getId());
		$this->assertNotEquals($weight->getId(),$height->getId());

		$this->assertEquals("weight","$weight");

		$weight->s(array(
			"name_en" => "Weight",
			"name_cs" => "Hmotnost",
		));

		$lang = "en";
		Atk14Locale::Initialize($lang);

		$this->assertEquals("Weight","$weight");

		$lang = "cs";
		Atk14Locale::Initialize($lang);

		$this->assertEquals("Hmotnost","$weight");
	}
}
