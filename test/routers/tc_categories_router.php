<?php
/**
 *
 * @fixture categories
 */
class TcCategoriesRouter extends TcBase {

	function test(){
		$this->router = new CategoriesRouter();

		// Building
		$uri = $this->assertBuildable(array(
			"lang" => "en",
			"controller" => "categories",
			"action" => "detail",
			"path" => "catalog/shoes",
			"offset" => 100
		),$params);
		$this->assertEquals("/catalog/shoes/",$uri);
		$this->assertEquals(array("offset" => 100),$params->toArray());

		$uri = $this->assertBuildable(array(
			"lang" => "cs",
			"controller" => "categories",
			"action" => "detail",
			"path" => "catalog/shoes",
			"offset" => 111
		),$params);
		$this->assertEquals("/katalog/obuv/",$uri);
		$this->assertEquals(array("offset" => 111),$params->toArray());

 		// path is missing
		$this->assertNotBuildable(array(
			"lang" => "en",
			"controller" => "categories",
			"action" => "detail",
			"offset" => 222,
		));

		// Recognizing
		$ret = $this->assertRecognizable("/catalog/shoes/",$params);
		$this->assertEquals("categories",$ret["controller"]);
		$this->assertEquals("detail",$ret["action"]);
		$this->assertEquals("en",$ret["lang"]);
		$this->assertEquals("catalog/shoes",$params["path"]);

		$ret = $this->assertRecognizable("/katalog/obuv/",$params);
		$this->assertEquals("categories",$ret["controller"]);
		$this->assertEquals("detail",$ret["action"]);
		$this->assertEquals("cs",$ret["lang"]);
		$this->assertEquals("katalog/obuv",$params["path"]);

		$this->assertNotRecognizable("/xxx/");
	}
}
