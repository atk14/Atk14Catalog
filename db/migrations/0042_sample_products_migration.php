<?php
class SampleProductsMigration extends ApplicationMigration {

	function up(){
		if(TEST){ return; }

		// Technical Specification Keys
		$width = TechnicalSpecificationKey::CreateNewRecord(array(
			"key" => "width",
			"key_localized_en" => "Width",
			"key_localized_cs" => "Šířka",
		));
		$height = TechnicalSpecificationKey::CreateNewRecord(array(
			"key" => "height",
			"key_localized_en" => "Height",
			"key_localized_cs" => "Výška",
		));

		// Product Card
		$card = Card::CreateNewRecord(array(
			'name_cs' => 'Nekonečný příběh',
			'name_en' => 'Neverending Story',
			'teaser_cs' => 'Nekonečný příběh (v německém originále Die Unendliche Geschichte) je fantasy román Michaela Endeho, poprvé vydaný v Německu roku 1979. V angličtině byl poprvé vydán roku 1983 v překladu Ralpha Manheima jako The Neverending Story. V češtině vydal Nekonečný příběh naposledy roku 2015 Albatros v překladu Evy Pátkové.',
			'teaser_en' => 'The Neverending Story (German: Die unendliche Geschichte) is a German fantasy novel by Michael Ende that was first published in 1979. An English translation, by Ralph Manheim, was first published in 1983. The novel was later adapted into several films.',
		));

		$card->addToCategory(Category::GetInstanceByPath("catalog/books"));
		$card->addToCategory(Category::GetInstanceByPath("catalog/books/children"));

		TechnicalSpecification::CreateNewRecord(array(
			"card_id" => $card,
			"technical_specification_key_id" => $width,
			"content" => "4.7 in",
			"content_localized_cs" => "12cm"
		));

		TechnicalSpecification::CreateNewRecord(array(
			"card_id" => $card,
			"technical_specification_key_id" => $height,
			"content" => "7.1 in",
			"content_localized_cs" => "17cm"
		));

		Image::AddImage($card,"http://i.pupiq.net/i/65/65/a53/1a53/756x1233/WSIRgf_756x1233_597a23f2092de822.jpg");
		Image::AddImage($card,"http://i.pupiq.net/i/65/65/a54/1a54/757x1224/VAmo0b_757x1224_8305397b852b605b.jpg");
		Image::AddImage($card,"http://i.pupiq.net/i/65/65/a55/1a55/528x1320/o8EAg6_528x1320_4fcb622fed692068.jpg");
		Image::AddImage($card,"http://i.pupiq.net/i/65/65/a56/1a56/1126x1600/L2FhUi_800x1137_80137f806bed8b93.jpg");
		Image::AddImage($card,"http://i.pupiq.net/i/65/65/a57/1a57/900x1198/gOVzrQ_800x1065_065b545455b10036.jpg");

		$section = CardSection::CreateNewRecord(array(
			'card_id' => $card,
			'card_section_type_id' => CardSectionType::FindByCode("info"),
  		'body_cs' => '### Děj

Kniha se zaměřuje na chlapce jménem Bastian Balthazar Bux, který v malém knihkupectví ukradne knihu s názvem Nekonečný příběh. Bastian většinou vystupuje jako čtenář příběhu jiného chlapce Átreje v zemi Fantazie - místa, kde se odehrávají příběhy lidské představivosti a setkávají se postavy z nich. Jak kniha pokračuje, vychází najevo, že někteří obyvatelé Fantázie jsou si vědomi Bastiana a že ten je klíčem k úkolu (zachránit Fantazii), o kterém zatím pouze čte. První část knihy je totiž v podstatě jen sledování cesty hrdiny Átreje. Skrz knihu se Bastian dostává do Fantázie a začíná tam hrát aktivní roli. Tak je zdramatizován zážitek „zachycení“ příběhem. V druhé polovině knihy se rozvíjí řada psychologicky bohatých témat: Bastian se musí vyrovnat s následky svých činů ve světě, který stvořil svými přáními, čelit své temné straně a vrátit se zpět do skutečného světa.

### Postavy

- Átrej (německy Atréju)
- Bastian Balthazar Bux
- Dětská císařovna / Měsíčnice (německy Die Kindliche Kaiserin/Mondenkind)
- Falko, šťastný pes (německy Fuchur, der Glücksdrache)
- Gmork, vlčí tvor sloužící Nicotě
- Ygramul, pavoučice
- Morlor, želva
- kamenožrout',
			'body_en' => '### Plot summary

The book centers on a boy, Bastian Balthazar Bux, a small and strange child who is neglected by his father after the death of Bastian\'s mother. While escaping from some bullies, Bastian bursts into the antique book store of Carl Conrad Coreander, where he finds his interest held by a book called The Neverending Story. Unable to resist, he steals the book and hides in his school\'s attic, where he begins to read.

The story Bastian reads is set in the magical land of Fantastica, an unrealistic place of wonder ruled by the benevolent and mysterious childlike Empress. A great delegation has come to the Empress to seek her help against a formless entity called "The Nothing". The delegates are shocked when the Empress\'s physician, a centaur named Cairon, informs them that the Empress is dying, and has summoned a boy warrior named Atreyu, to find a cure. To Atreyu, the Empress gives AURYN: a powerful medallion that protects him from all harm. At the advice of the giant turtle, Morla the Ancient One, Atreyu sets off in search of an invisible oracle known as Uyulala, who may know the Empress\'s cure. In reaching her, he is aided by a luckdragon named Falkor, whom he rescues from the monster \'Ygramul the Many\'. By Uyulala, he is told the only thing that can save the Empress is a new name given to her by a human child who can only be found beyond Fantastica\'s borders.

### Characters

- AURYN
- Atreyu
- Bastian Balthazar Bux
- The Childlike Empress/Moon Child
- Falkor, the luckdragon
- Carl Conrad Coreander
- Artax, Atreyu\'s horse
- Gmork, the wolf
- Morla, the giant turtle/the ancient one
- Uyulala, the invisible oracle
- Xayide, the witch
- Pyornkrachzark, the rock biter',
		));

		$section = CardSection::CreateNewRecord(array(
			'card_id' => $card,
			'card_section_type_id' => CardSectionType::FindByCode("documentation"),
			'name_cs' => 'Ukázka z knihy',
			'name_en' => 'Book sample',
		));

		Attachment::AddAttachment($section,array(
			"url" => "http://i.pupiq.net/a/65/65/9a3/9a3/1084697/never_ending_story_sample.pdf",
			"mime_type" => "application/pdf",
			"filesize" => "1084697",
			"name_cs" => "Nekonečný příběh, ukázka z knihy",
			"name_en" => "Neverending Story, book sample",
		));

		$section = CardSection::CreateNewRecord(array(
			'card_id' => $card,
			'card_section_type_id' => CardSectionType::FindByCode("tech_spec"),
			'name_cs' => 'Specifikace',
			'name_en' => 'Specifications',
		));

		Image::AddImage($section,"http://i.pupiq.net/i/65/65/a58/1a58/375x500/Tc3kLF_375x500_45f99cf7011a6c69.jpg");

		// Product
		$product = Product::CreateNewRecord(array(
			"card_id" => $card,
			"catalog_id" => "12345678",
		));
	}
}
