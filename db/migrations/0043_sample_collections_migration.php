<?php
class SampleCollectionsMigration extends ApplicationMigration{

	function up(){
		if(TEST){ return; }

		$c = Collection::CreateNewRecord(array(
			"name_en" => "Shaun the Sheep",
			"name_cs" => "Ovečka Shaun",

			"teaser_en" => "Products which Shaun the Sheep likes the most",
			"teaser_cs" => "Produkty, které se líbí ovečce Shaun",

			"description_en" => $this->_lipsumParagraphs(),
			"description_cs" => $this->_lipsumParagraphs(),

			"image_url" => "http://i.pupiq.net/i/65/65/a4a/1a4a/1000x1000/mjTeHv_800x800_7d3c8bbfbf363c8d.jpg"
		));
		Image::CreateNewRecord(array(
			"table_name" => "collections",
			"record_id" => $c,
			"url" => "http://i.pupiq.net/i/65/65/a4c/1a4c/800x465/9mRxoa_800x465_81f4477a3bf5f68e.jpg",

			"name_en" => "Shaun the Sheep is everywhere",
			"description_en" => "Shaun the Sheep is everywhere. Who doesn't believe is foolish.",

			"name_cs" => "Ovečka Shaun je všude",
			"description_cs" => "Ovečka Shaun je všude. Ten, kdo nevěří, je bláhový.",
		));
		Image::CreateNewRecord(array(
			"table_name" => "collections",
			"record_id" => $c,
			"url" => "http://i.pupiq.net/i/65/65/a4d/1a4d/1920x1280/MyJb59_800x533_3ddb82f191d55e61.jpg",

			"name_en" => "People love Shaun the Sheep",
			"description_en" => "Actually there is not any logical explanation.",

			"name_cs" => "Lidé milují Shaun",
			"description_cs" => "Vlastně pro to ani není žádné logické vysvětlení.",
		));


		Collection::CreateNewRecord(array(
			"name_en" => "Fidorka",
			"name_cs" => "Fidorka",

			"teaser_en" => "Products designed according to a popular czech chocolate biscuit",
			"teaser_cs" => "Produkty navržené podle oblíbeného českého čokoládového kolečka",

			"description_en" => $this->_lipsumParagraphs(),
			"description_cs" => $this->_lipsumParagraphs(),

			"image_url" => "http://i.pupiq.net/i/65/65/a4b/1a4b/600x600/7RAeN8_600x600_e6821f57da5c82d4.jpg"
		));
	}
}
