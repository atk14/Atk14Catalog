<?php
/**
 *
 * @fixture products
 */
class TcProduct extends TcBase {

	function test_getName(){
		// Black tea has not its own name filled
		$black_tea = $this->products["black_tea"];
		$this->assertEquals("Tea, black",$black_tea->getName());
		$this->assertEquals("Tea",$black_tea->getName(false));
		$this->assertEquals("Čaj",$black_tea->getName("cs",false));
		$this->assertEquals("Tea, black",$black_tea->getFullName());
		$this->assertEquals("Čaj, černý",$black_tea->getFullName("cs"));

		// Green tea has its own name filled
		$green_tea = $this->products["green_tea"];
		$this->assertEquals("Green tea",$green_tea->getName());
		$this->assertEquals("Green tea",$green_tea->getName(false));
		$this->assertEquals("Zelený čaj",$green_tea->getName("cs",false));
		$this->assertEquals("Green tea",$green_tea->getFullName());
		$this->assertEquals("Zelený čaj",$green_tea->getFullName("cs"));

		// Peanuts do not have a label filled nor own name
		// The name is read from the card
		$peanuts = $this->products["peanuts"];
		$this->assertEquals("Peanuts",$peanuts->getName());
		$this->assertEquals("Peanuts",$peanuts->getName(false));
		$this->assertEquals("Arašídy",$peanuts->getName("cs",false));
		$this->assertEquals("Peanuts",$peanuts->getFullName());
		$this->assertEquals("Arašídy",$peanuts->getFullName("cs"));
	}

	function test_destroy(){
		$green_tea = $this->products["green_tea"];
		$green_tea_id = $green_tea->getId();

		$this->assertEquals(false,$green_tea->isDeleted());
		$this->assertEquals("TEA_GREEN",$green_tea->getCatalogId());
		$this->assertEquals("TEA_GREEN",$green_tea->g("catalog_id"));

		$green_tea->destroy();

		$green_tea = Product::GetInstanceById($green_tea_id);
		$this->assertTrue(is_object($green_tea));
		$this->assertEquals(true,$green_tea->isDeleted());
		$this->assertEquals("TEA_GREEN",$green_tea->getCatalogId());
		$this->assertEquals("TEA_GREEN~deleted-$green_tea_id",$green_tea->g("catalog_id"));

		$gt = Product::GetInstanceByCatalogId("TEA_GREEN");
		$this->assertEquals($green_tea,$gt);

		$green_tea->destroy(true);

		$green_tea = Product::GetInstanceById($green_tea_id);
		$this->assertNull($green_tea);

		$gt = Product::GetInstanceByCatalogId("TEA_GREEN");
		$this->assertNull($gt);
	}
}
