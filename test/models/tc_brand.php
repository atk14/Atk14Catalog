<?php
class TcBrand extends TcBase{

	function setUp(){
		parent::setUp();
		DbMole::RegisterErrorHandler(function($dbmole){
			throw new Exception($dbmole->getErrorMessage());
		});
	}

	function test(){
		$snake_oil = Brand::CreateNewRecord(array(
			"name" => "Snake Oil"
		));
		$home_sweet_home = Brand::CreateNewRecord(array(
			"name" => "Home Sweet Home"
		));

		$this->assertEquals(false,$snake_oil->hasCards());
		$this->assertEquals(false,$home_sweet_home->hasCards());

		$this->assertEquals(true,$snake_oil->isDeletable());
		$this->assertEquals(true,$home_sweet_home->isDeletable());

		$massage_oil = Card::CreateNewRecord(array(
			"name_en" => "Massage Oil",
			"brand_id" => $snake_oil,
		));
		$tulip = Card::CreateNewRecord(array(
			"name_en" => "Tulip",
			"brand_id" => $home_sweet_home,
		));

		$this->assertEquals(true,$snake_oil->hasCards());
		$this->assertEquals(true,$home_sweet_home->hasCards());

		$this->assertEquals(false,$snake_oil->isDeletable());
		$this->assertEquals(false,$home_sweet_home->isDeletable());

		$massage_oil->s("deleted",true);

		$this->assertEquals(true,$snake_oil->isDeletable());
		$this->assertEquals(false,$home_sweet_home->isDeletable());

		$snake_oil->destroy();

		$massage_oil = Card::GetInstanceById($massage_oil);
		$tulip = Card::GetInstanceById($tulip);

		$this->assertEquals(null,$massage_oil->getBrandId());
		$this->assertEquals($home_sweet_home->getId(),$tulip->getBrandId());

		$this->assertEquals(null,Brand::GetInstanceById($snake_oil->getId())); // the Snake Oil has been really deleted from the database

		// try to delete a brand with non-deleted cards should leads to an exception
		try{
			@$home_sweet_home->destroy();
			$this->fail();
		}catch(Exception $e){
			$msg = $e->getMessage();
			$this->assertContains("fk_cards_brands",$msg); // failed to execute SQL query pg_last_error: ERROR:  update or delete on table "brands" violates foreign key constraint "fk_cards_brands" on table "cards"
		}
	}
}
