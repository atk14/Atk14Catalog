<?php
class BrandsFillingMigration extends ApplicationMigration{
	function up(){
		$b = Brand::CreateNewRecord(array(
			"name" => "Snake Oil Co.",

			"teaser_en" => "Fraudulent health products and fake drugs",
			"teaser_cs" => "Podvodné zdravotní produkty a falešné léky",

			"description_en" => $this->_lipsumParagraphs(),
			"description_cs" => $this->_lipsumParagraphs(),

			"logo_url" => "http://www.fillmurray.com/400/400"
		));
		Image::CreateNewRecord(array(
			"table_name" => "brands",
			"record_id" => $b,
			"url" => "http://www.fillmurray.com/800/800",

			"name_en" => "Good choice",
			"description_en" => "Snake Oil Co. is a good choice. There is not question about it.",

			"name_cs" => "Skvělá volba",
			"description_cs" => "Snake Oil Co. je skvělá volba. O tom není pochyb.",
		));
		Image::CreateNewRecord(array(
			"table_name" => "brands",
			"record_id" => $b,
			"url" => "http://www.fillmurray.com/g/800/800",

			"name_en" => "Huge variety of goods",
			"description_en" => "Snake Oil Co. offers huge variery of goods.",

			"name_cs" => "Obrovské množství zboží",
			"description_cs" => "Snake Oil Co. nabízí obrovské množství zboží",
		));

		$b = Brand::CreateNewRecord(array(
			"name" => "Bouncy Castle",

			"teaser_en" => "A leading, worldwide manufacturer of inflatable with bouncy castles for sale",
			"teaser_cs" => "Přední světový výrobce nafukovacích skákacích hradů",

			"description_en" => $this->_lipsumParagraphs(),
			"description_cs" => $this->_lipsumParagraphs(),

			"logo_url" => "http://www.fillmurray.com/g/400/400"
		));
	}
}
