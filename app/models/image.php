<?php
class Image extends ApplicationModel implements Rankable, Translatable{

	static function GetTranslatableFields(){ return array("name","description"); }


	static function CreateNewRecord($values,$options = array()){
		assert(
			(get_called_class()=="Image" && $values["table_name"]!="products") ||
			(get_called_class()=="ProductImage" && $values["table_name"]=="products")
		);
		return parent::CreateNewRecord($values,$options);
	}

	/**
	 * $images = Image::GetImages($product);
	 */
	static function GetImages($obj,$options = array()){
		$options += array("use_cache" => true);

		$class_name = "Image";
		is_a($obj,"Product") && ($class_name = "ProductImage");

		return $class_name::FindAll("table_name",$obj->getTableName(),"record_id",$obj->getId(),$options);
	}

	/**
	 * Image::AddImage($product,array("url" => "http://...."));
	 * Image::AddImage($product,array("url" => $pupiq));
	 *
	 * Image::AddImage($product,"http://....");
	 */
	static function AddImage($obj,$values){
		if(is_string($values)){
			$values = array("url" => $values);
		}
		$class_name = "Image";
		is_a($obj,"Product") && ($class_name = "ProductImage");
		$values["table_name"] = $obj->getTableName();
		$values["record_id"] = $obj->getId();
		return $class_name::CreateNewRecord($values);
	}

	function toString(){ return $this->g("url"); }

	function getOriginalWidth(){
		return $this->_getPupiq()->getOriginalWidth();
	}

	function getOriginalHeight(){
		return $this->_getPupiq()->getOriginalHeight();
	}

	function setRank($new_rank){
		return $this->_setRank($new_rank,array(
			"table_name" => $this->g("table_name"),
			"record_id" => $this->g("record_id"),
		));
	}

	function _getPupiq(){
		if(!isset($this->_pupiq)){
			$this->_pupiq = new Pupiq($this->getUrl());
		}
		return $this->_pupiq;
	}

	static function DeleteObjectImages($obj){
		foreach(Image::GetImages($obj) as $i){
			$i->destroy();
		}
	}
}
