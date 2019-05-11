<?php
/**
 *
 * @fixture categories
 */
class TcCategoryField extends TcBase {

	function test(){
		$this->field = $f = new CategoryField(array(
			"required" => true,
			//"treat_null_as_root" => false, // this is default
		));

		$value = $this->assertValid("/catalog/food-drinks/hot-drinks/");
		$this->assertEquals($this->categories["hot_drinks"]->getId(),$value->getId());

		$msg = $this->assertInvalid("");
		$this->assertEquals($f->messages["required"],$msg);

		$msg = $this->assertInvalid("/");
		$this->assertEquals($f->messages["no_such_category"],$msg);

		$msg = $this->assertInvalid("/nonsence/");
		$this->assertEquals($f->messages["no_such_category"],$msg);

		//

		$this->field = new CategoryField(array(
			"required" => true,
			"treat_null_as_root" => true,
		));

		$value = $this->assertValid("/catalog/shoes/");
		$this->assertEquals($this->categories["shoes"]->getId(),$value->getId());

		$msg = $this->assertInvalid("");
		$this->assertEquals($f->messages["required"],$msg);

		$value = $this->assertValid("/");
		$this->assertNull($value);

		$msg = $this->assertInvalid("/nonsence/");
		$this->assertEquals($f->messages["no_such_category"],$msg);
	}
}
