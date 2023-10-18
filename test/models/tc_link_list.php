<?php
/**
 *
 * @fixture link_lists
 * @fixture link_list_items
 */
class TcLinkList extends TcBase {

	function test(){
		$test_list = $this->link_lists["test_list"];

		$this->assertEquals(5,sizeof($test_list->getItems()));
		$this->assertEquals(3,sizeof($test_list->getVisibleItems()));

		$this->assertEquals(false,$test_list->isEmpty());
		$this->assertEquals(false,$test_list->isEmpty(array("consider_visibility" => true)));
		$this->assertEquals(false,$test_list->isEmpty(array("consider_visibility" => false)));
		$this->assertEquals(false,$test_list->isEmpty(true));
		$this->assertEquals(false,$test_list->isEmpty(false));

		$items = $test_list->getVisibleItems();
		$items[0]->s("visible",false);
		$items[1]->s("visible",false);
		$items[2]->s("visible",false);

		$this->assertEquals(5,sizeof($test_list->getItems()));
		$this->assertEquals(0,sizeof($test_list->getVisibleItems()));

		$this->assertEquals(true,$test_list->isEmpty());
		$this->assertEquals(true,$test_list->isEmpty(array("consider_visibility" => true)));
		$this->assertEquals(false,$test_list->isEmpty(array("consider_visibility" => false)));
		$this->assertEquals(true,$test_list->isEmpty(true));
		$this->assertEquals(false,$test_list->isEmpty(false));
	}

	function test_asSubmenu(){
		$list = $this->link_lists["test_list"];
		$submenu = $list->asSubmenu();
		$this->assertEquals(3,sizeof($submenu->getItems()));

		$list = $this->link_lists["empty_list"];
		$submenu = $list->asSubmenu();
		$this->assertEquals(null,$submenu);

		$list = $this->link_lists["list_with_invisible_items"];
		$submenu = $list->asSubmenu();
		$this->assertEquals(null,$submenu);
	}
}
