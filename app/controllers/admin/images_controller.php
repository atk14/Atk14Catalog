<?php
class ImagesController extends AdminController{
	function index(){
		$this->page_title = sprintf(_("Photo gallery of the object %s#%s"),$this->table_name,$this->record_id);
		$this->tpl_data["images"] = Image::FindAll("table_name",$this->table_name,"record_id",$this->record_id);

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
		$this->page_title = _("Adding an image into the photo gallery");

		$this->_save_return_uri();
		if($this->request->post() && ($d = $this->form->validate($this->params))){

			$pupiq = $d["file"]; unset($d["file"]);

			$d["table_name"] = $this->table_name;
			$d["record_id"] = $this->record_id;
			$d["url"] = $pupiq->getUrl();

			$image = Image::CreateNewRecord($d);

			if($this->request->xhr()){
				$this->render_template = false;
				$this->response->write(json_encode($this->_dump_image($image)));
				$this->response->setContentType("text/plain");
				return;
			}

			$this->flash->success(_("Image was created"));
			$this->_redirect_back();
		}
	}

	function edit(){
		$this->page_title = sprintf(_("Editing of the Image #%s"),$this->image->getId());

		$this->_save_return_uri();
		$this->form->set_initial($this->image);

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			$this->image->s($d);

			$this->flash->success(_("Changes have been saved"));
			$this->_redirect_back();
		}
	}

	function destroy(){
		if(!$this->request->post()){
			return $this->_execute_action("error404");
		}

		$this->image->destroy();

		if(!$this->request->xhr()){
			$this->flash->success(_("The image has been deleted"));
			$this->_redirect_back();
		}
	}

	function set_rank(){
		if(!$this->request->post()){ return $this->_execute_action("error404"); }

		$this->render_template = false;
		$this->image->setRank($this->params->getInt("rank"));
	}

	function _before_filter(){
		// potrebujeme tyto 2 parametry
		if(
			in_array($this->action,array("index","create_new")) && (
				!($this->table_name = $this->tpl_data["table_name"] = (string)$this->params["table_name"]) ||
				!($this->record_id = $this->tpl_data["record_id"] = (int)$this->params["record_id"])
			)
		){
			return $this->_execute_action("error404");
		}

		if(in_array($this->action,array("destroy","set_rank","edit"))){
			if(!$image = $this->_just_find("image")){
				return $this->_execute_action("error404");
			}
			$this->image = $this->tpl_data["image"] = $image;
			$this->table_name = $image->g("table_name");
			$this->record_id = $image->getRecordId();
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

	function _dump_image($image){
		$pupiq = new Pupiq($image->getUrl());
		$pupiq->setGeometry("x60");
		return array(
			"id" => $image->getId(),
			"url" => $pupiq->getUrl(),
			"width" => $pupiq->getWidth(),
			"height" => $pupiq->getHeight(),
			"original_geometry" => array(
				"width" => $pupiq->getOriginalWidth(),
				"height" => $pupiq->getOriginalHeight(), 
			),
			"image_gallery_item" => $this->_render("shared/image_gallery_item",array("image" => $image)),
		);
	}
}
