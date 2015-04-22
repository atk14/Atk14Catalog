<?php
class  CardSectionsController extends AdminController{
	function create_new(){
		$this->page_title = sprintf(_("Add a textual section for the product %s"),h($this->card->getName()));

		$this->_save_return_uri();

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			$d["card_id"] = $this->card;
			$cs = CardSection::CreateNewRecord($d);
			$this->flash->success(_("The section has been created").'<br><a href="'.$this->_link_to(array("action" => "card_sections/edit", "id" => $cs)).'">'._("It would be nice to add some more information")."</a>");
			$this->_redirect_back();
		}
	}

	function edit(){
		$this->page_title = _("Editace textové sekce");

		$this->form->set_initial($this->card_section);
		$this->_save_return_uri();

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			$this->card_section->s($d);
			$this->flash->success(_("Změny byly uloženy"));
			$this->_redirect_back(array(
				"action" => "cards/edit",
				"id" => $this->card_section->getCard(),
			));
		}
	}

	function destroy(){
		if(!$this->request->post()){ return $this->_execute_action("error404"); }
		$this->card_section->destroy();
	}

	function set_rank(){
		if(!$this->request->post()){ return $this->_execute_action("error404"); }

		$this->render_template = false;
		$this->card_section->setRank($this->params->getInt("rank"));
	}

	function _setup_breadcrumbs_filter() {
		$card = null;
		isset($this->card) && ($card = $this->card);
		(!$card && $this->card_section) && ($card = $this->card_section->getCard());

		$this->breadcrumbs->addItem(_("Produkty"), $this->_link_to("cards/index"));
		$this->breadcrumbs->addItem(sprintf(_("Karta produktu '%s' (id: %d)"), $card->getName(),$card->getId()), $this->_link_to(array("controller" => "cards", "action" => "edit", "id" => $card)));

		($this->action=="edit") && $this->breadcrumbs->addTextItem( sprintf(_("Textová sekce '%s'"), $this->card_section->getName()) );
		($this->action=="create_new") && $this->breadcrumbs->addTextItem( _("Nová textová sekce") );
	}

	function _before_filter(){
		$this->action=="create_new" && $this->_find("card","card_id");
		in_array($this->action,array("edit","destroy","set_rank")) && $this->_find("card_section");
	}

	function _redirect_back($default = null){
		if(is_null($default)){
			isset($this->card) && ($card_id = $this->card->getId());
			isset($this->card_section) && ($card_id = $this->card_section->getCardId()) ;
			$default = array(
				"controller" => "cards",
				"action" => "edit",
				"id" => $card_id
			);
		}
		return parent::_redirect_back($default);
	}
}
