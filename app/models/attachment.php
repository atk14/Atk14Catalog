<?php
class Attachment extends LinkedObject implements Translatable{

	use TraitPupiqAttachment;

	static function GetTranslatableFields(){ return array("name"); }

	/**
	 * $attachments = Attachment::GetAttachments($card_section);
	 */
	static function GetAttachments($obj){
		return Attachment::FindAll("table_name",$obj->getTableName(),"record_id",$obj->getId());
	}

	function getName($lang = null){
		if($name = parent::getName($lang)){
			return $name;
		}
		return $this->getFilename();
	}

	function getSuffix(){
		return $this->_getPupiqAttachment()->getSuffix();
	}

	static function AddAttachment($obj,$values,$options = array()){
		if(is_string($values)){
			$values = array("url" => $values);
		}
		return Attachment::CreateNewFor($obj,$values,$options);
	}
}
