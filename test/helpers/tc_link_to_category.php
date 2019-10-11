<?php
/**
 *
 * @fixture categories
 */
class TcLinkToCategory extends TcBase {

	function test(){
		global $ATK14_GLOBAL;

		Atk14Require::Helper("modifier.link_to_category");

		$ATK14_GLOBAL->setValue("lang","en");

		$link = smarty_modifier_link_to_category("food_drinks");
		$this->assertEquals("/catalog/food-drinks/",$link);

		$link = smarty_modifier_link_to_category("food_drinks","with_hostname");
		$this->assertEquals("http://".ATK14_HTTP_HOST."/catalog/food-drinks/",$link);

		$link = smarty_modifier_link_to_category("weird_code");
		$this->assertEquals("/en/main/page_not_found/?category=weird_code",$link);

		$ATK14_GLOBAL->setValue("lang","cs");

		$link = smarty_modifier_link_to_category("food_drinks");
		$this->assertEquals("/katalog/jidlo-a-napoje/",$link);

		$link = smarty_modifier_link_to_category("weird_code");
		$this->assertEquals("/cs/main/page_not_found/?category=weird_code",$link);

		$link = smarty_modifier_link_to_category("weird_code","with_hostname");
		$this->assertEquals("http://".ATK14_HTTP_HOST."/cs/main/page_not_found/?category=weird_code",$link);
	}
}
