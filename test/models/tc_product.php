<?php
/**
 *
 * @fixture products
 */
class TcProduct extends TcBase {

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
