<?php
class TcSlug extends TcBase{
	function test(){
		global $ATK14_GLOBAL;
		
		$this->assertEquals(null,Slug::GetRecordIdBySlug("brands","atk14-framework"));

		$atk14 = Brand::CreateNewRecord(array(
			"name" => "ATK14 Framework",
		));

		// zatim slug neni ulozen ->je vracen genericky
		$this->assertEquals("atk14-framework",Slug::GetObjectSlug($atk14));
		$ATK14_GLOBAL->setValue("lang","en");
		$this->assertEquals("atk14-framework",Slug::GetObjectSlug($atk14));
		$ATK14_GLOBAL->setValue("lang","cs");

		// ulozime slugy
		Slug::SetObjectSlug($atk14,"atk14-ramecek","cs");
		Slug::SetObjectSlug($atk14,"atk14-framework","en");

		$this->assertEquals("atk14-ramecek",Slug::GetObjectSlug($atk14));
		$ATK14_GLOBAL->setValue("lang","en");
		$this->assertEquals("atk14-framework",Slug::GetObjectSlug($atk14));
		$ATK14_GLOBAL->setValue("lang","cs");

		//var_dump($atk14->toArray());
		//var_dump($this->dbmole->selectRows("SELECT * FROM slugs"));

		$lang = null;
		$this->assertEquals($atk14->getId(),Slug::GetRecordIdBySlug("brands","atk14-framework",$lang));
		$this->assertEquals("en",$lang);

		$lang = null;
		$this->assertEquals($atk14->getId(),Slug::GetRecordIdBySlug("brands","atk14-ramecek",$lang));
		$this->assertEquals("cs",$lang);

		$ATK14_GLOBAL->setValue("lang","cs");
		Slug::SetObjectSlug($atk14,"atk14-framework-nice"); // uklada se pro default lang, coz je en
		$this->assertEquals("atk14-ramecek",Slug::GetObjectSlug($atk14)); // toto ale vraci akt. jazyk, coz je cs
		$this->assertEquals("atk14-framework-nice",Slug::GetObjectSlug($atk14,"en"));

		Slug::DeleteObjectSlugs($atk14);

		// po smazani to zase vraci genericke nazvy
		$this->assertEquals("brands-cs-".$atk14->getId(),Slug::GetObjectSlug($atk14));
		$this->assertEquals("brands-en-".$atk14->getId(),Slug::GetObjectSlug($atk14,"en"));

		$ATK14_GLOBAL->setValue("lang","cs");

		$parent_page = StaticPage::CreateNewRecord(array(
			"title_cs" => "Hlavní stránka",
			"title_en" => "Main page"
		));
		$this->assertEquals(array("cs" => "hlavni-stranka", "en" => "main-page"),$parent_page->getSlugs());
		$this->assertEquals(array("slug_cs" => "hlavni-stranka", "slug_en" => "main-page"),$parent_page->getSlugs(array("prefix" => "slug_")));

		$static_page = StaticPage::CreateNewRecord(array(
			"title_cs" => "Statická stránka",
			"parent_static_page_id" => $parent_page,
		));
		$this->assertEquals("staticka-stranka",$static_page->getSlug());
		$this->assertEquals("hlavni-stranka/staticka-stranka",$static_page->getPath());

		$static_page->s("parent_static_page_id",null);
		$this->assertEquals("staticka-stranka",$static_page->getSlug());
		$this->assertEquals("staticka-stranka",$static_page->getPath());

		$static_page->s("slug_cs","staticka-stranecka");
		$this->assertEquals("staticka-stranecka",$static_page->getSlug());
		$this->assertEquals("staticka-stranecka",$static_page->getPath());

		$static_page->s(array("slug_cs" => "staticka-stranecicka", "parent_static_page_id" => $parent_page));
		$this->assertEquals("staticka-stranecicka",$static_page->getSlug());
		$this->assertEquals("hlavni-stranka/staticka-stranecicka",$static_page->getPath());
		
		Slug::DeleteObjectSlugs($static_page);

		$this->assertTrue(!!preg_match("/^static-pages-cs-\d+/", (string)Slug::GetObjectSlug($static_page)));
	}

	function test_usage_in_models(){
		global $ATK14_GLOBAL;

		$dior = Brand::CreateNewRecord(array("name" => "Dior"));
		$armani = Brand::CreateNewRecord(array("name" => "Armani"));

		$this->assertEquals("dior",$dior->getSlug());
		$this->assertEquals("armani",$armani->getSlug());

		$this->assertEquals("dior",$dior->getSlug("cs"));
		$this->assertEquals("armani",$armani->getSlug("cs"));

		$this->assertEquals("dior",$dior->getSlug("en"));
		$this->assertEquals("armani",$armani->getSlug("en"));

		$dior->setSlug("dior-cs","cs");
		$armani->setSlug("armani-cs","cs");

		$dior->setSlug("dior-en","en");
		$armani->setSlug("armani-en","en");

		$this->assertEquals("dior-cs",$dior->getSlug());
		$this->assertEquals("armani-cs",$armani->getSlug());

		$this->assertEquals("dior-en",$dior->getSlug("en"));
		$this->assertEquals("armani-en",$armani->getSlug("en"));

		$ATK14_GLOBAL->setValue("lang","en");

		$this->assertEquals("dior-en",$dior->getSlug());
		$this->assertEquals("armani-en",$armani->getSlug());

		// instantiating

		$lang = null;
		$b = Brand::GetInstanceBySlug("dior-en",$lang);
		$this->assertEquals($dior->getId(),$b->getId());
		$this->assertEquals("en",$lang);

		$lang = "cs";
		$b = Brand::GetInstanceBySlug("dior-cs",$lang);
		$this->assertEquals($dior->getId(),$b->getId());
		$this->assertEquals("cs",$lang);

		$lang = "en";
		$this->assertNull(Brand::GetInstanceBySlug("dior-cs",$lang));
		$this->assertEquals("en",$lang);

		$lang = "cs";
		$this->assertNull(Brand::GetInstanceBySlug("brands-en-".$dior->getId(),$lang));
		$this->assertEquals("cs",$lang);

		$lang = "cs";
		$b = Brand::GetInstanceBySlug("brands-cs-".$dior->getId(),$lang);
		$this->assertEquals($dior->getId(),$b->getId());
		$this->assertEquals("cs",$lang);

		$lang = null;
		$b = Brand::GetInstanceBySlug("brands-en-".$dior->getId(),$lang);
		$this->assertEquals($dior->getId(),$b->getId());
		$this->assertEquals("en",$lang);
	}

	function test_transparent_usage_in_models(){
		global $ATK14_GLOBAL;
		$ATK14_GLOBAL->setValue("lang","cs");

		// Brand ma nastaveno Brand::$automatically_sluggable na true
		$dior = Brand::CreateNewRecord(array(
			"name" => "Dior",
			"slug_cs" => "parfemy-dior",
			"slug_en" => "parfumes-dior",
		));

		$this->assertEquals("parfemy-dior",$dior->getSlug());
		$this->assertEquals("parfemy-dior",$dior->getSlug("cs"));
		$this->assertEquals("parfumes-dior",$dior->getSlug("en"));

		$ATK14_GLOBAL->setValue("lang","en");
		$this->assertEquals("parfumes-dior",$dior->getSlug());
		$this->assertEquals("parfemy-dior",$dior->getSlug("cs"));
		$this->assertEquals("parfumes-dior",$dior->getSlug("en"));

		$dior->s(array(
			"slug_cs" => "dior-parfemy",
			"slug_en" => "dior-parfumes",
		));

		$ATK14_GLOBAL->setValue("lang","cs");
		$this->assertEquals("dior-parfemy",$dior->getSlug());
		$this->assertEquals("dior-parfemy",$dior->getSlug("cs"));
		$this->assertEquals("dior-parfumes",$dior->getSlug("en"));

		$this->assertEquals(2,$this->dbmole->selectInt("SELECT COUNT(*) FROM slugs WHERE table_name=:table_name AND record_id=:record_id",array(":table_name" => "brands", ":record_id" => $dior)));

		$dior_id = $dior->getId();

		$dior->s(array(
			"slug_cs" => "dior-parfemy",
			"slug_en" => "brands-en-$dior_id",
		));

		$this->assertEquals(1,$this->dbmole->selectInt("SELECT COUNT(*) FROM slugs WHERE table_name=:table_name AND record_id=:record_id",array(":table_name" => "brands", ":record_id" => $dior)));

		$dior->s(array("slug_cs" => ""));

		$this->assertEquals(0,$this->dbmole->selectInt("SELECT COUNT(*) FROM slugs WHERE table_name=:table_name AND record_id=:record_id",array(":table_name" => "brands", ":record_id" => $dior)));

		$this->assertEquals("brands-en-$dior_id",$dior->getSlug("en"));
		$this->assertEquals("brands-cs-$dior_id",$dior->getSlug("cs"));

		// testing reconstruct_missing_slugs option

		$dior->s(array(
			"name" => "Dior & Co",
		),array(
			"reconstruct_missing_slugs" => true
		));

		$this->assertEquals(0,$this->dbmole->selectInt("SELECT COUNT(*) FROM slugs WHERE table_name=:table_name AND record_id=:record_id",array(":table_name" => "brands", ":record_id" => $dior)));

		$dior->s(array(
			"slug_en" => "",
		),array(
			"reconstruct_missing_slugs" => true
		));

		$this->assertEquals(1,$this->dbmole->selectInt("SELECT COUNT(*) FROM slugs WHERE table_name=:table_name AND record_id=:record_id",array(":table_name" => "brands", ":record_id" => $dior)));

		$dior->s(array(
			"slug_cs" => null,
		),array(
			"reconstruct_missing_slugs" => true
		));

		$this->assertEquals(2,$this->dbmole->selectInt("SELECT COUNT(*) FROM slugs WHERE table_name=:table_name AND record_id=:record_id",array(":table_name" => "brands", ":record_id" => $dior)));
	}
}
