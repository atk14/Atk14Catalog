<?php
class FulfillingPagesMigration extends Atk14Migration{

	function up(){
		$about = Page::CreateNewRecord(array(
			"id" => 1, // we just need that the page #1 is the About Page
			"code" => "about_us",

			"title_en" => "About Us",
			"body_en" => "It all begins when a young boy meets a ...",

			"title_cs" => "O nás",
			"body_cs" => "Všechno to začalo, když jeden mladý muž potkal...",
		));

		$this->dbmole->doQuery("ALTER SEQUENCE seq_pages RESTART WITH 2");

		$media = Page::CreateNewRecord(array(
			"parent_page_id" => $about,
			
			"title_en" => "For Media",
			"body_en" => "Currently we have no information for media.",

			"title_cs" => "Pro média",
			"body_cs" => "V této chvíli nemáme pro média žádné informace."
		));

		$contact_data = Page::CreateNewRecord(array(
			"parent_page_id" => $about,
			"code" => "contact",
			
			"title_en" => "Contact Data",
			"body_en" => trim("
## Address

    Elm Street 1428
    Springwood
    Ohio
    United States
"),

			"title_cs" => "Kontaktní údaje",
			"body_cs" => "
## Adresa

    Elm Street 1428
    Springwood
    Ohio
    United States
"
		));
	}
}
