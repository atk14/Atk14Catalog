<?php
class AttachmentsController extends AdminController{
	function index(){
		$this->page_title = sprintf(_("Přílohy k objektu %s#%s"),$this->table_name,$this->record_id);
		$this->tpl_data["attachments"] = Attachment::FindAll("table_name",$this->table_name,"record_id",$this->record_id);

		$url_back = "";
		switch($this->table_name){
			case "card_sections":
				($cs = CardSection::GetInstanceById($this->record_id)) &&
				($url_back = $this->_link_to(array("action" => "cards/edit", "id" => $cs->getCardId())));
				break;
			case "designers":
				($ds = Designer::GetInstanceById($this->record_id)) &&
				($url_back = $this->_link_to(array("action" => "designers/edit", "id" => $ds)));
				break;	
		}
		$this->tpl_data["url_back"] = $url_back;
	}

	function create_new(){
		$this->page_title = _("Přidání přílohy");

		$this->_save_return_uri();
		if($this->request->post() && ($d = $this->form->validate($this->params))){

			$attachment = $d["attachment"];
			unset($d["attachment"]);

			$d["table_name"] = $this->table_name;
			$d["record_id"] = $this->record_id;

			$d["filesize"] = $attachment->getFilesize();
			$d["mime_type"] = $attachment->getMimeType();
			$d["url"] = $attachment->getUrl();

			//var_dump($d); exit;

			Attachment::CreateNewRecord($d);

			$this->flash->success(_("Příloha byla uložena"));
			$this->_redirect_back();
		}
	}

	function edit(){
		$this->page_title = sprintf(_("Editace přílohy #%s"),$this->attachment->getId());

		$this->_save_return_uri();
		$this->form->set_initial($this->attachment);

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			$this->attachment->s($d);
			$this->flash->success(_("Změny byly uloženy"));
			$this->_redirect_back();
		}
	}

	function destroy(){
		if(!$this->request->post()){
			return $this->_execute_action("error404");
		}

		$this->attachment->destroy();

		if(!$this->request->xhr()){
			$this->flash->success(_("Obrázek byl smazán."));
			$this->_redirect_back();
		}
	}

	function set_rank(){
		if(!$this->request->post()){ return $this->_execute_action("error404"); }

		$this->render_template = false;
		$this->attachment->setRank($this->params->getInt("rank"));
	}

	function _before_filter(){

		if(in_array($this->action,array("destroy","set_rank","edit"))){
			$this->_find("attachment");
		}
		// potrebujeme tyto 2 parametry
		if ((
			in_array($this->action,array("index","create_new")) && (
				!($this->table_name = $this->tpl_data["table_name"] = (string)$this->params["table_name"]) ||
				!($this->record_id = $this->tpl_data["record_id"] = (int)$this->params["record_id"])
			)
		) || (
			in_array($this->action,array("edit")) && (
				!($this->table_name = $this->tpl_data["table_name"] = (string)$this->attachment->g("table_name")) ||
				!($this->record_id = $this->tpl_data["record_id"] = (int)$this->attachment->g("record_id"))
			)
		)) {
			return $this->_execute_action("error404");
		}
	}

	function _redirect_back($default = null){
		if(!$default){
			$default = array(
				"action" => "index",
				"table_name" => $this->table_name,
				"record_id" => $this->record_id,
			);
		}
		return parent::_redirect_back($default);
	}
}
