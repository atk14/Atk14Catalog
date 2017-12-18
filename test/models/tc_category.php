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

		$child->s("visible",true);
		$this->assertTrue($root->isVisible());
		$this->assertTrue($child->isVisible());

		Cache::Clear();

		$root->s("visible",false);
		$this->assertFalse($root->isVisible());
		$this->assertFalse($child->isVisible());
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

		// Testing that addCard() inserts the given card at the beginning of the list
		
		$hot_drinks->addCard($coffee);
		$hot_drinks->addCard($tea);

		$cards = $hot_drinks->getCards();
		$this->assertEquals(2,sizeof($cards));
		$this->assertEquals($tea->getId(),$cards[0]->getId());
		$this->assertEquals($coffee->getId(),$cards[1]->getId());

		// --

		$food_drinks->addCard($tea);
		$food_drinks->addCard($coffee);

		$cards = $food_drinks->getCards();
		$this->assertEquals(2,sizeof($cards));
		$this->assertEquals($coffee->getId(),$cards[0]->getId());
		$this->assertEquals($tea->getId(),$cards[1]->getId());
	}
}
