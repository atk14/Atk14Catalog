<?php
class TcBase extends TcAtk14Router {

	function _setUp(){
		$this->dbmole->begin();
		$this->setUpFixtures();
	}

	function _tearDown(){
		$this->dbmole->rollback();
	}
}
