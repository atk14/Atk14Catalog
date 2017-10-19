<?php
class TechnicalSpecificationsController extends AdminController {

	function create_new(){
		$this->page_title = _("Add new technical specification");

		$this->_save_return_uri();
		$this->_add_card_to_breadcrumbs($this->card);

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			if(TechnicalSpecification::FindFirst("card_id",$this->card,"technical_specification_key_id",$d["technical_specification_key_id"])){
				$this->form->set_error("technical_specification_key_id",_("The product already has this specification"));
				return;
			}

			$d["card_id"]	= $this->card;
			TechnicalSpecification::CreateNewRecord($d);

			$this->flash->success(_("New technical specification record has been created"));

			$this->_redirect_back();
		}
	}

	function set_rank(){
		$this->_set_rank();
	}

	function edit(){
		$this->_add_card_to_breadcrumbs($this->technical_specification->getCard());

		$this->_edit(array(
			"update_closure" => function($object,$d){
				$existing_spec = TechnicalSpecification::FindFirst("card_id",$object->getCardId(),"technical_specification_key_id",$d["technical_specification_key_id"]);
				if($existing_spec && $existing_spec->getId()!=$object->getId()){
					$this->form->set_error("technical_specification_key_id",_("The product already has this specification"));
					return;
				}
				$object->s($d);
			}
		));
	}

	function destroy(){
		$this->_destroy();
	}

	function _before_filter(){
		if($this->action=="create_new"){
			$this->_find("card","card_id");
		}

		if($this->action=="edit"){
			$this->_find("technical_specification");
		}
	}
}
