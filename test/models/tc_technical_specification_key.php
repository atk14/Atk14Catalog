<?php
class TcTechnicalSpecificationKey extends TcBase {

	function test(){
		$weight = TechnicalSpecificationKey::GetOrCreateKey("weight");
		$weight2 = TechnicalSpecificationKey::GetOrCreateKey("Weight");
		$height = TechnicalSpecificationKey::GetOrCreateKey("Height");

		$this->assertEquals("weight",$weight->getName());
		$this->assertEquals("weight",$weight2->getName());

		$this->assertEquals($weight->getId(),$weight2->getId());
		$this->assertNotEquals($weight->getId(),$height->getId());
	}
}
