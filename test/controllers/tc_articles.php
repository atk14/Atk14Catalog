<?php
/**
 *
 * @fixture articles
 */
class TcArticles extends TcBase {

	function test(){
		$this->client->get("articles/detail",array("id" => $this->articles["testing_article"]->getId()));
		$this->assertStringContains(">Testing Article</h1>",$this->client->getContent());
		$this->assertStringContains("<title>Testing Article",$this->client->getContent());
		$this->assertStringContains('<meta name="description" content="Testing teaser">',$this->client->getContent());

		$this->client->get("articles/detail",array("id" => $this->articles["interesting_article"]->getId()));
		$this->assertStringContains(">Interesting Article</h1>",$this->client->getContent());
		$this->assertStringContains("<title>Page title",$this->client->getContent());
		$this->assertStringContains('<meta name="description" content="Page description">',$this->client->getContent());
	}
}
