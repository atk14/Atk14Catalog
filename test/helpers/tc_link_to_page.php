<?php
/**
 *
 * @fixture pages 
 */
class TcLinkToPage extends TcBase {

	function test(){
		global $ATK14_GLOBAL;

		Atk14Require::Helper("modifier.link_to_page");

		$ATK14_GLOBAL->setValue("lang","en");
		$this->assertEquals("/testing-page/",smarty_modifier_link_to_page("testing"));

		$this->assertEquals("#",smarty_modifier_link_to_page("non_existing_page"));
	}
}
