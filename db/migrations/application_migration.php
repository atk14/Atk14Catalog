<?php
/**
 * The base class for all PHP migrations
 *
 * The perfect place for common methods (e.g. lorem ipsum generator)
 */
class ApplicationMigration extends Atk14Migration{

	/**
	 * Returns random lorem_ipsum paragraphs
	 *
	 * It uses www.lipsum.com API.
	 */
	protected function _lipsumParagraphs($amount = 2, $connector = "\n\n"){
		$uf = new UrlFetcher("http://www.lipsum.com/feed/xml?amount=$amount&what=paras&start=0");
		$lipsum = simplexml_load_string($uf->getContent())->lipsum;
		return str_replace("\n",$connector,$lipsum);
	}

}
