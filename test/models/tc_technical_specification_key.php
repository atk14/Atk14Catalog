<?php
class TcTechnicalSpecificationKey extends TcBase {

	function test(){
		$this->assertNull(TechnicalSpecificationKey::FindByKey("weight")); // record doesn't exist

		$weight = TechnicalSpecificationKey::GetOrCreateKey("weight");
		$weight2 = TechnicalSpecificationKey::GetOrCreateKey("Weight");
		$height = TechnicalSpecificationKey::GetOrCreateKey("Height");

		$this->assertEquals("weight",$weight->getKey());
		$this->assertEquals("weight",$weight2->getKey());

		$this->assertEquals($weight->getId(),$weight2->getId());
		$this->assertNotEquals($weight->getId(),$height->getId());

		$this->assertEquals("weight","$weight");

		$weight->s(array(
			"key_localized_en" => "Weight",
			"key_localized_cs" => "Hmotnost",
		));

		$this->assertEquals("Weight",$weight->getKey());
		$this->assertEquals("Weight",$weight->getKey("en"));
		$this->assertEquals("Hmotnost",$weight->getKey("cs"));

		$lang = "en";
		Atk14Locale::Initialize($lang);

		$this->assertEquals("Weight","$weight");

		$lang = "cs";
		Atk14Locale::Initialize($lang);

		$this->assertEquals("Hmotnost","$weight");
	}
}
