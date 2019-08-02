<?php
class ProductImage extends Image {

	function __construct(){
		parent::__construct("product_images",array(
			"sequence_name" => "seq_images",
		));
	}

	static function CreateNewRecord($values,$options = array()){
		myAssert($values["table_name"]=="products");
		return parent::CreateNewRecord($values,$options);
	}

	function displayOnCard(){
		return $this->getDisplayOnCard();
	}
}
