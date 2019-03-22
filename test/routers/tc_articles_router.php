<?php
/**
 *
 * @fixture articles
 */
class TcArticlesRouter extends TcBase {

	function test(){
		$this->router = new ArticlesRouter();

		// Building

		$uri = $this->assertBuildable(array(
			"lang" => "en",
			"controller" => "articles",
			"action" => "detail",
			"id" => $this->articles["testing_article"]->getId(),
		));
		$this->assertEquals("/article/testing-article/",$uri);

		$uri = $this->assertBuildable(array(
			"lang" => "cs",
			"controller" => "articles",
			"action" => "detail",
			"id" => $this->articles["testing_article"]->getId(),
		));
		$this->assertEquals("/clanek/testovaci-clanek/",$uri);

		$this->assertNotBuildable(array(
			"lang" => "cs",
			"controller" => "articles",
			"action" => "detail",
		));

		// Recognizing

		$ret = $this->assertRecognizable("/article/testing-article/",$params);
		$this->assertEquals("articles",$ret["controller"]);
		$this->assertEquals("detail",$ret["action"]);
		$this->assertEquals("en",$ret["lang"]);
		$this->assertEquals($this->articles["testing_article"]->getId(),$params["id"]);

		$ret = $this->assertNotRecognizable("/bad-prefix/testing-article/");

		$ret = $this->assertNotRecognizable("/article/non-existing-article/");
	}
}
