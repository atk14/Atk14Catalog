<?php
/**
 *
 * @fixture categories
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
}
