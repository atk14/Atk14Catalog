<?php
class TcCatalogIdField extends TcBase {

	function test(){
		$this->field = $f = new CatalogIdField(array());

		$catalog_id = $this->assertValid("PROD123");
		$this->assertEquals("PROD123",$catalog_id);

		$catalog_id = $this->assertValid("prod123"); // since CATALOG_ID_AUTO_UPPERIZE is true
		$this->assertEquals("PROD123",$catalog_id);

		$err = $this->assertInvalid("$$%");
		$this->assertEquals($f->messages["invalid"],$err);
	}
}
