<?php
/**
 *
 * @fixture articles
 */
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

		$parent_page = Page::CreateNewRecord(array(
			"title_cs" => "Hlavní stránka",
			"title_en" => "Main page"
		));
		$this->assertEquals(array("cs" => "hlavni-stranka", "en" => "main-page"),$parent_page->getSlugs());
		$this->assertEquals(array("slug_cs" => "hlavni-stranka", "slug_en" => "main-page"),$parent_page->getSlugs(array("prefix" => "slug_")));

		$page = Page::CreateNewRecord(array(
			"title_cs" => "Stránka",
			"parent_page_id" => $parent_page,
		));
		$this->assertEquals("stranka",$page->getSlug());
		$this->assertEquals("hlavni-stranka/stranka",$page->getPath());

		$page->s("parent_page_id",null);
		$this->assertEquals("stranka",$page->getSlug());
		$this->assertEquals("stranka",$page->getPath());

		$page->s("slug_cs","staticka-stranecka");
		$this->assertEquals("staticka-stranecka",$page->getSlug());
		$this->assertEquals("staticka-stranecka",$page->getPath());

		$page->s(array("slug_cs" => "staticka-stranecicka", "parent_page_id" => $parent_page));
		$this->assertEquals("staticka-stranecicka",$page->getSlug());
		$this->assertEquals("hlavni-stranka/staticka-stranecicka",$page->getPath());
		
		Slug::DeleteObjectSlugs($page);

		$this->assertTrue(!!preg_match("/^pages-cs-\d+/", (string)Slug::GetObjectSlug($page)));
	}

	function test_usage_in_models(){
		global $ATK14_GLOBAL;

		$ATK14_GLOBAL->setValue("lang","cs");

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

		// Brand implements iSlug
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

	function test_StringToSluggish(){
		$this->assertEquals("hell-yeah",Slug::StringToSluggish("Hell Yeah! :)"));
		$this->assertEquals("hell-yeah-123",Slug::StringToSluggish("Hell Yeah! :)","123"));

		$very_long_name = str_repeat("a",SLUG_MAX_LENGTH * 2); // 400 x a
		$slug = Slug::StringToSluggish($very_long_name,"456");
		$this->assertEquals(SLUG_MAX_LENGTH,strlen($slug));
		$this->assertTrue(!!preg_match('/aaa-456$/',$slug));
	}

	function test_handle_slug_collision(){
		$a1 = Article::CreateNewRecord(array(
			"title_en" => "Sample Article",
		));
		$this->assertEquals("sample-article",$a1->getSlug("en"));

		$a2 = Article::CreateNewRecord(array(
			"title_en" => "Another Sample Article",
		));
		$this->assertEquals("another-sample-article",$a2->getSlug("en"));

		$a3 = Article::CreateNewRecord(array(
			"title_en" => "Sample Article",
		));
		$this->assertEquals("sample-article-2",$a3->getSlug("en"));

		// There is an internal limit 50, after that uniqid() is added to the slug
		for($i=1;$i<=52;$i++){
			$a = Article::CreateNewRecord(array("title_en" => "Nice Reading"));
			$expected_slug = "nice-reading";
			if($i>=2 && $i<=50){
				$expected_slug .= "-$i";
			}
			if($i>50){
				$this->assertTrue(!!preg_match('/^nice-reading-[a-z0-9]{5,}$/',$a->getSlug("en")));
				return;
			}
			$this->assertEquals($expected_slug,$a->getSlug("en"));
		}
	}

	function test_reconstruct_missing_slugs(){
		// false
		$article = Article::CreateNewRecord(array(
			"title_en" => "Nice Reading",
			"title_cs" => "Krásné čtení",
			"slug_cs" => "krasne-cteni"
		),array(
			"reconstruct_missing_slugs" => false,
		));
		$this->assertEquals("articles-en-".$article->getId(),$article->getSlug("en"));
		$this->assertEquals("krasne-cteni",$article->getSlug("cs"));

		// true
		$article = Article::CreateNewRecord(array(
			"title_en" => "Very Nice Reading",
			"title_cs" => "Moc krásné čtení",
			"slug_cs" => "moc-krasne-cteni"
		),array(
			"reconstruct_missing_slugs" => true,
		));
		$this->assertEquals("very-nice-reading",$article->getSlug("en"));
		$this->assertEquals("moc-krasne-cteni",$article->getSlug("cs"));

		// defaul
		$article = Article::CreateNewRecord(array(
			"title_en" => "Very, Very Nice Reading",
			"title_cs" => "Moc, moc krásné čtení",
			"slug_cs" => "moc-moc-krasne-cteni"
		));
		$this->assertEquals("very-very-nice-reading",$article->getSlug("en"));
		$this->assertEquals("moc-moc-krasne-cteni",$article->getSlug("cs"));
	}

	function test_GetRecordIdBySlug(){
		$testing_article = $this->articles["testing_article"];
		$interesting_article = $this->articles["interesting_article"];

		$testing_article_with_testing_segment = Article::CreateNewRecord(array("slug_en" => null, "slug_cs" => null));

		Slug::CreateNewRecord(array(
			"table_name" => "articles",
			"record_id" => $testing_article_with_testing_segment,
			"lang" => "en",
			"segment" => "testing-segment",
			"slug" => "testing-article-with-testing-segment"
		));

		$id = Slug::GetRecordIdBySlug("articles","testing-article");
		$this->assertEquals($testing_article->getId(),$id);

		$id = Slug::GetRecordIdBySlug("articles","testovaci-clanek");
		$this->assertEquals($testing_article->getId(),$id);

		// language detection

		$lang = null;
		$id = Slug::GetRecordIdBySlug("articles","testing-article",$lang);
		$this->assertEquals($testing_article->getId(),$id);
		$this->assertEquals("en",$lang);

		$lang = null;
		$id = Slug::GetRecordIdBySlug("articles","testovaci-clanek",$lang);
		$this->assertEquals($testing_article->getId(),$id);
		$this->assertEquals("cs",$lang);

		// correct language passed

		$lang = "en";
		$id = Slug::GetRecordIdBySlug("articles","testing-article",$lang);
		$this->assertEquals($testing_article->getId(),$id);
		$this->assertEquals("en",$lang);

		$lang = "cs";
		$id = Slug::GetRecordIdBySlug("articles","testovaci-clanek",$lang);
		$this->assertEquals($testing_article->getId(),$id);
		$this->assertEquals("cs",$lang);

		// slug & lang mismatch

		$lang = "cs";
		$id = Slug::GetRecordIdBySlug("articles","testing-article",$lang);
		$this->assertNull($id);

		$lang = "en";
		$id = Slug::GetRecordIdBySlug("articles","testovaci-clanek",$lang);
		$this->assertNull($id);

		// correct segment passed

		$lang = "en";
		$id = Slug::GetRecordIdBySlug("articles","testing-article",$lang,"");
		$this->assertEquals($testing_article->getId(),$id);
		$this->assertEquals("en",$lang);

		$lang = null;
		$id = Slug::GetRecordIdBySlug("articles","testovaci-clanek",$lang,"");
		$this->assertEquals($testing_article->getId(),$id);
		$this->assertEquals("cs",$lang);

		//

		$lang = null;
		$id = Slug::GetRecordIdBySlug("articles","testing-article-with-testing-segment",$lang);
		$this->assertNull($id);

		$lang = null;
		$id = Slug::GetRecordIdBySlug("articles","testing-article-with-testing-segment",$lang,array("segment" => ""));
		$this->assertNull($id);

		$lang = null;
		$id = Slug::GetRecordIdBySlug("articles","testing-article-with-testing-segment",$lang,"testing-segment");
		$this->assertEquals($testing_article_with_testing_segment->getId(),$id);
		$this->assertEquals("en",$lang);

		$lang = null;
		$id = Slug::GetRecordIdBySlug("articles","testing-article-with-testing-segment",$lang,array("segment" => "testing-segment"));
		$this->assertEquals($testing_article_with_testing_segment->getId(),$id);
		$this->assertEquals("en",$lang);

		// invalid segment

		$lang = "en";
		$id = Slug::GetRecordIdBySlug("articles","testing-article",$lang,"xx");
		$this->assertNull($id);

		$lang = null;
		$id = Slug::GetRecordIdBySlug("articles","testovaci-clanek",$lang,"xx");
		$this->assertNull($id);

		// searching without segment

		$lang = "en";
		$id = Slug::GetRecordIdBySlug("articles","testing-article",$lang,array("consider_segment" => false));
		$this->assertEquals($testing_article->getId(),$id);
		$this->assertEquals("en",$lang);

		$lang = null;
		$id = Slug::GetRecordIdBySlug("articles","testovaci-clanek",$lang,array("consider_segment" => false));
		$this->assertEquals($testing_article->getId(),$id);
		$this->assertEquals("cs",$lang);

		$lang = null;
		$id = Slug::GetRecordIdBySlug("articles","testing-article-with-testing-segment",$lang,array("consider_segment" => false));
		$this->assertEquals($testing_article_with_testing_segment->getId(),$id);
		$this->assertEquals("en",$lang);

		// deleting record (cache must be cleared)

		$id = Slug::GetRecordIdBySlug("articles","interesting-article");
		$this->assertEquals($interesting_article->getId(),$id);

		$testing_article->destroy();

		$id = Slug::GetRecordIdBySlug("articles","testing-article");
		$this->assertNull($id);

		$lang = null;
		$id = Slug::GetRecordIdBySlug("articles","testing-article",$lang,null);
		$this->assertNull($id);

		$id = Slug::GetRecordIdBySlug("articles","interesting-article");
		$this->assertEquals($interesting_article->getId(),$id);

		// usage in a model

		$lang = null;
		$article = Article::GetInstanceBySlug("interesting-article",$lang);
		$this->assertEquals($interesting_article->getId(),$article->getId());
		$this->assertEquals("en",$lang);

		$lang = "cs";
		$article = Article::GetInstanceBySlug("interesting-article",$lang);
		$this->assertNull($article);

		$lang = null;
		$article = Article::GetInstanceBySlug("interesting-article",$lang,"");
		$this->assertEquals($interesting_article->getId(),$article->getId());
		$this->assertEquals("en",$lang);

		$lang = null;
		$article = Article::GetInstanceBySlug("interesting-article",$lang,null);
		$this->assertEquals($interesting_article->getId(),$article->getId());
		$this->assertEquals("en",$lang);

		$lang = null;
		$article = Article::GetInstanceBySlug("interesting-article",$lang,array("segment" => ""));
		$this->assertEquals($interesting_article->getId(),$article->getId());
		$this->assertEquals("en",$lang);

		$lang = null;
		$article = Article::GetInstanceBySlug("interesting-article",$lang,array("segment" => "bad-segment"));
		$this->assertNull($article);

		$lang = null;
		$article = Article::GetInstanceBySlug("testing-article-with-testing-segment",$lang);
		$this->assertNull($article);

		$lang = null;
		$article = Article::GetInstanceBySlug("testing-article-with-testing-segment",$lang,"testing-segment");
		$this->assertEquals($testing_article_with_testing_segment->getId(),$article->getId());

		$lang = null;
		$article = Article::GetInstanceBySlug("testing-article-with-testing-segment",$lang,array("segment" => "testing-segment"));
		$this->assertEquals($testing_article_with_testing_segment->getId(),$article->getId());

		$lang = null;
		$article = Article::GetInstanceBySlug("testing-article-with-testing-segment",$lang,array("consider_segment" => false));
		$this->assertEquals($testing_article_with_testing_segment->getId(),$article->getId());
	}

	function test_generic_slugs(){
		$article = $this->articles["testing_article"];
		$id = $article->getId();
		$article->s("slug_cs",null);

		$this->assertEquals("testing-article",$article->getSlug());
		$this->assertEquals("articles-cs-$id",$article->getSlug("cs"));

		$lang = null;
		$a = Article::GetInstanceBySlug("testing-article",$lang);
		$this->assertEquals($article->getId(),$a->getId());
		$this->assertEquals("en",$lang);

		$lang = null;
		$a = Article::GetInstanceBySlug("articles-cs-$id",$lang);
		$this->assertEquals($article->getId(),$a->getId());
		$this->assertEquals("cs",$lang);

		$lang = "en";
		$a = Article::GetInstanceBySlug("articles-cs-$id",$lang);
		$this->assertEquals(null,$a);

		$lang = null;
		$a = Article::GetInstanceBySlug("articles-en-$id",$lang);
		$this->assertEquals($article->getId(),$a->getId());
		$this->assertEquals("en",$lang);
	}
}
