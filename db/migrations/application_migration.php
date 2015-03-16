<?php
class ApplicationMigration extends Atk14Migration{

	/**
	 * Returns random lorem_ipsum paragraphs
	 *
	 * It uses www.lipsum.com API.
	 */
	protected function _lipsumParagraphs($amount = 2, $connector = "\n\n"){
		$lipsum = simplexml_load_file("http://www.lipsum.com/feed/xml?amount=$amount&what=paras&start=0")->lipsum;
		return str_replace("\n",$connector,$lipsum);
	}
}
