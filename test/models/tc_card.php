<?php
/**
 *
 * @fixture cards
 * @fixture categories
 * @fixture category_cards
 */
class TcCard extends TcBase {

	function test(){
		$tea = $this->cards["tea"];
		$catalog = $this->categories["catalog"];
		$food_drinks = $this->categories["food_drinks"];
		$color = $this->categories["color"];
		$color_green = $this->categories["color_green"];
		$hot_drinks = $this->categories["hot_drinks"];

		// Testing Card::getCategories()

		$categories = $tea->getCategories();
		$this->assertEquals(2,sizeof($categories));
		$this->assertEquals($color_green->getId(),$categories[0]->getId());
		$this->assertEquals($hot_drinks->getId(),$categories[1]->getId());

		$categories = $tea->getCategories(array("consider_filters" => false));
		$this->assertEquals(1,sizeof($categories));
		$this->assertEquals($hot_drinks->getId(),$categories[0]->getId());

		$categories = $tea->getCategories(array("root_category" => $catalog));
		$this->assertEquals(2,sizeof($categories));

		$categories = $tea->getCategories(array("root_category" => $food_drinks));
		$this->assertEquals(1,sizeof($categories));

		$categories = $tea->getCategories(array("filters_only" => true));
		$this->assertEquals(1,sizeof($categories));
		$this->assertEquals($color_green->getId(),$categories[0]->getId());

		// Testing Card::getActiveFilters()

		$filters = $tea->getActiveFilters();
		$this->assertEquals(1,sizeof($filters));
		$this->assertEquals($color->getId(),$filters[0]->getId());
	}

	function test_canBeSwitchedToNonVariantMode(){
		$card = Card::CreateNewRecord(array());
		$this->assertTrue($card->canBeSwitchedToNonVariantMode());

		$card = Card::CreateNewRecord(array());
		$card->createProduct(array("catalog_id" => "123"));
		$this->assertTrue($card->canBeSwitchedToNonVariantMode());

		$card = Card::CreateNewRecord(array());
		$card->createProduct(array("catalog_id" => "124"));
		$card->createProduct(array("catalog_id" => "125"));
		$this->assertFalse($card->canBeSwitchedToNonVariantMode());

		$card = Card::CreateNewRecord(array());
		$card->createProduct(array("catalog_id" => "126"));
		$card->createProduct(array("catalog_id" => "127","deleted" => true));
		$this->assertTrue($card->canBeSwitchedToNonVariantMode());
	}
}
