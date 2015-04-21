<?php
class Attachment extends ApplicationModel implements Translatable,Rankable{

	static function GetTranslatableFields(){ return array("name", "description"); }

	/**
	 * $attachments = Attachment::GetAttachments($card_section);
	 */
	static function GetAttachments($obj){
		return Attachment::FindAll("table_name",$obj->getTableName(),"record_id",$obj->getId());
	}

	function setRank($new_rank){
		return $this->_setRank($new_rank,array(
			"table_name" => $this->g("table_name"),
			"record_id" => $this->g("record_id"),
		));
	}

	function getName($lang = null){
		if($name = parent::getName($lang)){
			return $name;
		}
		return $this->getFilename();
	}

	function getFilename(){
		// http://a.pupiq.net/priloha/31/DSC_0078.JPG -> DSC_0078.JPG
		return preg_replace('/^.+\/([^\/]+)$/','\1',$this->getUrl());
	}

	static function AddAttachment($obj,$values){
		if(is_string($values)){
			$values = array("url" => $values);
		}
		$values["table_name"] = $obj->getTableName();
		$values["record_id"] = $obj->getId();
		return Attachment::CreateNewRecord($values);
	}
}
