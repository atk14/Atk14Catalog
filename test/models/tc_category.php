<?php
/**
 *
 * @fixture categories
 * @fixture cards
 */
class TcCategory extends TcBase {

	function test_isVisible(){
		$root = Category::CreateNewRecord(array());
		$this->assertTrue($root->isVisible());

		$child = Category::CreateNewRecord(array(
			"parent_category_id" => $root,
			"visible" => false,
		));
		$this->assertTrue($root->isVisible());
		$this->assertFalse($child->isVisible());
		$this->assertFalse($child->isVisible(false));

		$child->s("visible",true);
		$this->assertTrue($root->isVisible());
		$this->assertTrue($child->isVisible());
		$this->assertTrue($child->isVisible(false));

		Cache::Clear();

		$root->s("visible",false);
		$this->assertFalse($root->isVisible());
		$this->assertFalse($child->isVisible());
		$this->assertTrue($child->isVisible(false));
	}

	function test_isDescendantOf(){
		$categories = $this->categories;

		$this->assertTrue($categories["color_red"]->isDescendantOf($categories["catalog"]));
		$this->assertTrue($categories["color_red"]->isDescendantOf($categories["color"]));
		$this->assertTrue($categories["color_red"]->isDescendantOf($categories["color_red"]));

		$this->assertFalse($categories["color_red"]->isDescendantOf($categories["shoes"]));
	}

	function test_addCard(){
		$hot_drinks = $this->categories["hot_drinks"];
		$food_drinks = $this->categories["food_drinks"];

		$coffee = $this->cards["coffee"];
		$tea = $this->cards["tea"];
		$apple_cider = $this->cards["apple_cider"];

		// Testing that addCard() inserts the given card at the beginning of the list
		
		$hot_drinks->addCard($coffee);
		$hot_drinks->addCard($tea);

		$cards = $hot_drinks->getCards();
		$this->assertEquals(2,sizeof($cards));
		$this->assertEquals($tea->getId(),$cards[0]->getId());
		$this->assertEquals($coffee->getId(),$cards[1]->getId());

		// --

		$food_drinks->addCard($tea);
		$food_drinks->addCard($coffee,["first" => true]); // this is the default
		$food_drinks->addCard($apple_cider,array("first" => false));

		$cards = $food_drinks->getCards();
		$this->assertEquals(3,sizeof($cards));
		$this->assertEquals($coffee->getId(),$cards[0]->getId());
		$this->assertEquals($tea->getId(),$cards[1]->getId());
		$this->assertEquals($apple_cider->getId(),$cards[2]->getId());
	}

	function test_names(){
		$shoes = $this->categories["shoes"];

		$this->assertEquals("Shoes",$shoes->getName("en"));
		$this->assertEquals("Obuv",$shoes->getName("cs"));

		$this->assertEquals("Shoes",$shoes->getLongName("en"));
		$this->assertEquals("Obuv",$shoes->getLongName("cs"));

		$this->assertEquals("Shoes",$shoes->getPageTitle("en"));
		$this->assertEquals("Obuv",$shoes->getPageTitle("cs"));

		// --

		$mens_shoes = $this->categories["mens_shoes"];

		$this->assertEquals("Men",$mens_shoes->getName("en"));
		$this->assertEquals("Muži",$mens_shoes->getName("cs"));

		$this->assertEquals("Men's shoes",$mens_shoes->getLongName("en"));
		$this->assertEquals("Obuv pro muže",$mens_shoes->getLongName("cs"));

		$this->assertEquals("Men's shoes",$mens_shoes->getPageTitle("en"));
		$this->assertEquals("Obuv pro muže",$mens_shoes->getPageTitle("cs"));
	}

	function test_destroy(){
		$kids = $this->categories["kids"];
		$kids__kids_shoes = $this->categories["kids__kids_shoes"];
		//
		$kids_shoes = $this->categories["kids_shoes"];
		$kids_shoes__girls = $this->categories["kids_shoes__girls"];
		
		$this->assertEquals($kids->getId(),$kids__kids_shoes->getParentCategoryId());
		$this->assertEquals($kids_shoes->getId(),$kids__kids_shoes->getPointingToCategoryId());
		$this->assertEquals($kids_shoes->getId(),$kids_shoes__girls->getParentCategoryId());

		$kids->destroy();

		Cache::Clear();

		$this->assertNull(Category::FindByCode("kids"));
		$this->assertNull(Category::FindByCode("kids__kids_shoes"));

		$this->assertNotNull(Category::FindByCode("kids_shoes"));
		$this->assertNotNull(Category::FindByCode("kids_shoes__girls"));
	}

	function test_GetInstanceByPath(){
		$lang = null;
		$cat = Category::GetInstanceByPath("catalog/shoes/kids",$lang);
		$this->assertEquals($this->categories["kids_shoes"]->getId(),$cat->getId());
		$this->assertEquals("en",$lang);

		$lang = null;
		$cat = Category::GetInstanceByPath("katalog/obuv/deti",$lang);
		$this->assertEquals($this->categories["kids_shoes"]->getId(),$cat->getId());
		$this->assertEquals("cs",$lang);

		$lang = "en";
		$cat = Category::GetInstanceByPath("catalog/shoes/kids",$lang);
		$this->assertEquals($this->categories["kids_shoes"]->getId(),$cat->getId());

		$lang = "cs";
		$cat = Category::GetInstanceByPath("catalog/shoes/kids",$lang);
		$this->assertNull($cat);

		$lang = null;
		$cat = Category::GetInstanceByPath("nonsence/nonsence",$lang);
		$this->assertNull($cat);
		
		$lang = null;
		$cat = Category::GetInstanceByPath("catalog/obuv/deti",$lang); // language mixing
		$this->assertNull($cat);

		// GetInstancesOnPath

		$lang = null;
		$cats = Category::GetInstancesOnPath("catalog/shoes/kids",$lang);
		$this->assertEquals(3,sizeof($cats));
		$this->assertEquals("catalog",$cats[0]->getSlug());
		$this->assertEquals("shoes",$cats[1]->getSlug());
		$this->assertEquals("kids",$cats[2]->getSlug());
		$this->assertEquals("en",$lang);

		$lang = null;
		$cats = Category::GetInstancesOnPath("catalog/shoes/kids/nonsence",$lang);
		$this->assertEquals(null,$cats);
	}

	function test_GetInstanceByNamePath(){

		// GetInstancesOnPath without parent category

		$lang = null;
		$cat = Category::GetInstanceByName(null,"Catalog",$lang);
		$this->assertEquals($this->categories["catalog"]->getId(),$cat->getId());
		$this->assertEquals("en",$lang);

		$lang = "en";
		$cat = Category::GetInstanceByName(null,"Catalog",$lang);
		$this->assertEquals($this->categories["catalog"]->getId(),$cat->getId());
		$this->assertEquals("en",$lang);

		$lang = null;
		$cat = Category::GetInstanceByName(null,"Katalog",$lang);
		$this->assertEquals($this->categories["catalog"]->getId(),$cat->getId());
		$this->assertEquals("cs",$lang);

		$lang = "en";
		$cat = Category::GetInstanceByName(null,"Katalog",$lang);
		$this->assertNull($cat);

		// GetInstancesOnPath with parent category

		$catalog = $this->categories["catalog"];

		$lang = null;
		$cat = Category::GetInstanceByName($catalog,"Shoes",$lang);
		$this->assertEquals($this->categories["shoes"]->getId(),$cat->getId());
		$this->assertEquals("en",$lang);

		$lang = null;
		$cat = Category::GetInstanceByName($catalog,"Obuv",$lang);
		$this->assertEquals($this->categories["shoes"]->getId(),$cat->getId());
		$this->assertEquals("cs",$lang);

		$lang = null;
		$cat = Category::GetInstanceByName($catalog,"Catalog",$lang); // nonsence
		$this->assertNull($cat);

		// GetInstanceByNamePath

		$lang = null;
		$cat = Category::GetInstanceByNamePath("Catalog/Shoes/Kids",$lang);
		$this->assertEquals($this->categories["kids_shoes"]->getId(),$cat->getId());
		$this->assertEquals("en",$lang);

		$lang = null;
		$cat = Category::GetInstanceByNamePath("Katalog/Obuv/Děti",$lang);
		$this->assertEquals($this->categories["kids_shoes"]->getId(),$cat->getId());
		$this->assertEquals("cs",$lang);

		$lang = null;
		$cat = Category::GetInstanceByNamePath("Katalog/Shoes/Děti",$lang); // language mixing
		$this->assertNull($cat);

		// GetInstancesOnNamePath

		$lang = null;
		$cats = Category::GetInstancesOnNamePath("Catalog/Shoes/Kids",$lang);
		$this->assertEquals(3,sizeof($cats));
		$this->assertEquals("catalog",$cats[0]->getSlug());
		$this->assertEquals("shoes",$cats[1]->getSlug());
		$this->assertEquals("kids",$cats[2]->getSlug());
		$this->assertEquals("en",$lang);

		$lang = null;
		$cats = Category::GetInstancesOnNamePath("Catalog/Shoes/Kids/Nonsence",$lang);
		$this->assertEquals(null,$cats);
	}
}
