<?php
class TechnicalSpecificationKeysController extends AdminController {

	function edit(){
		$this->_edit(array(
			"page_title" => sprintf(_("Editace klíče %s"),$this->technical_specification_key->getKey()),
			"object" => $this->technical_specification_key,
			"update_closure" => function($object,$d){
				if(($k = TechnicalSpecificationKey::GetInstanceByKey($d["key"])) && $k->getId()!=$object->getId()){
					$this->form->set_error("key",_("The same key already exists"));
					return;
				}
				$object->s($d);
			}
		));
	}

	function _before_filter(){
		$this->_find("technical_specification_key");
	}
}
