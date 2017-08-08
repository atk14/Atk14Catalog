<?php
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
}
