<?php
class TcLoginField extends TcBase {

	function test(){
		$this->field = new LoginField();

		$login = $this->assertValid("john.doe");
		$this->assertEquals("john.doe",$login);

		$login = $this->assertValid("SAMANTHA92");
		$this->assertEquals("samantha92",$login);
	}

	function test_help_text(){
		$field = new LoginField();
		$this->assertStringContains("Up to 50 characters.",$field->help_text);

		$field = new LoginField(array("max_length" => 32));
		$this->assertStringContains("Up to 32 characters.",$field->help_text);
	}
}
