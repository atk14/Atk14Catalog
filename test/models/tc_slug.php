<?php
/**
 *
 * @fixture articles
 */
class TcSlug extends TcBase {

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
}
