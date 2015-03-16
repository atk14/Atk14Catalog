<?php
class FulfillingCollectionsMigration extends ApplicationMigration{
	function up(){
		$c = Collection::CreateNewRecord(array(
			"name_en" => "Shaun the Sheep",
			"name_cs" => "Ovečka Shaun",

			"teaser_en" => "Products which Shaun the Sheep likes the most",
			"teaser_cs" => "Produkty, které se líbí ovečce Shaun",

			"description_en" => $this->_lipsumParagraphs(),
			"description_cs" => $this->_lipsumParagraphs(),

			"image_url" => "http://www.fillmurray.com/500/500"
		));
		Image::CreateNewRecord(array(
			"table_name" => "collections",
			"record_id" => $c,
			"url" => "http://www.fillmurray.com/800/800",

			"name_en" => "Shaun the Sheep is everywhere",
			"description_en" => "Shaun the Sheep is everywhere. Who doesn't believe is foolish.",

			"name_cs" => "Ovečka Shaun je všude",
			"description_cs" => "Ovečka Shaun je všude. Ten, kdo nevěří, je bláhový.",
		));
		Image::CreateNewRecord(array(
			"table_name" => "collections",
			"record_id" => $c,
			"url" => "http://www.fillmurray.com/g/800/800",

			"name_en" => "People love Shaun the Sheep",
			"description_en" => "Actually there is not any logical explanation.",

			"name_cs" => "Lidé milují Shaun",
			"description_cs" => "Vlastně pro to ani není žádné logické vysvětlení.",
		));


		Collection::CreateNewRecord(array(
			"name_en" => "Fidorka",
			"name_cs" => "Fidorka",

			"teaser_en" => "Products designed according to a popular czech chocolate biscuit",
			"teaser_cs" => "Produkty nevržené podle oblíbeného českého čokoládového kolečka",

			"description_en" => $this->_lipsumParagraphs(),
			"description_cs" => $this->_lipsumParagraphs(),

			"image_url" => "http://www.fillmurray.com/g/500/500"
		));
	}
}
