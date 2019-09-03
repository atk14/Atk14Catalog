<?php
class SampleBrandsMigration extends ApplicationMigration{

	function up(){
		if(TEST){ return; }

		$b = Brand::CreateNewRecord(array(
			"name" => "Snake Oil Co.",

			"teaser_en" => "Fraudulent health products and fake drugs",
			"teaser_cs" => "Podvodné zdravotní produkty a falešné léky",

			"description_en" => $this->_lipsumParagraphs(),
			"description_cs" => $this->_lipsumParagraphs(),

			"logo_url" => "http://i.pupiq.net/i/65/65/a3c/1a3c/330x250/TBIaGW_330x250_ef0d908d21a09b35.jpg"
		));
		Image::CreateNewRecord(array(
			"table_name" => "brands",
			"record_id" => $b,
			"url" => "http://i.pupiq.net/i/65/65/a3d/1a3d/867x1024/UMGcNn_800x945_20d6ef34dd635fdd.jpg",

			"name_en" => "Good choice",
			"description_en" => "Snake Oil Co. is a good choice. There is not question about it.",

			"name_cs" => "Skvělá volba",
			"description_cs" => "Snake Oil Co. je skvělá volba. O tom není pochyb.",
		));
		Image::CreateNewRecord(array(
			"table_name" => "brands",
			"record_id" => $b,
			"url" => "http://i.pupiq.net/i/65/65/a3e/1a3e/1024x683/qcBwAP_800x534_80aa59eb71cfa266.jpg",

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

			"logo_url" => "http://i.pupiq.net/i/65/65/a3f/1a3f/960x840/G6rbqH_800x700_e713550de0b44678.jpg"
		));
	}
}
