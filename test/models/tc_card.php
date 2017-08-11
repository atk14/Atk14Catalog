<?php
/**
 *
 * @fixture cards
 * @fixture categories
 * @fixture category_cards
 */
class TcCard extends TcBase {

	function test_getCategories(){
		$tea = $this->cards["tea"];
		$catalog = $this->categories["catalog"];
		$food_drinks = $this->categories["food_drinks"];
		$color_green = $this->categories["color_green"];
		$hot_drinks = $this->categories["hot_drinks"];

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
	}
}
