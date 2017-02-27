<?php
class TechnicalSpecificationsController extends AdminController {

	function create_new(){
		$this->page_title = _("Add new technical specification");

		$this->_save_return_uri();

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			if(TechnicalSpecification::FindFirst("card_id",$this->card,"technical_specification_key_id",$d["technical_specification_key_id"])){
				$this->form->set_error("technical_specification_key_id",_("The product already has this specification"));
				return;
			}

			$d["card_id"]	= $this->card;
			TechnicalSpecification::CreateNewRecord($d);

			$this->flash->success(_("New technical specification records has been created"));

			$this->_redirect_back();
		}
	}

	function set_rank(){
		$this->_set_rank();
	}

	function edit(){
		$this->_edit();
	}

	function _before_filter(){
		if($this->action=="create_new"){
			$this->_find("card","card_id");
		}
	}
}
