<?php
class InformationRequestsController extends ApplicationController{

	function create_new(){
		$this->page_title = $this->breadcrumbs[] = _("Request for information");

		$this->_save_return_uri();

		$this->form->set_initial("body",$this->_prepare_initial_text($this->card,$this->product));

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			if($d["sign_up_for_newsletter"] && $d["email"]){
				NewsletterSubscriber::SignUp($d["email"],array(
					"name" => $d["name"],
				));
			}

			$d["product"] = $this->product;
			$d["card"] = $this->card;
			$this->mailer->send_information_request($d,$this->request,$this->logged_user);
			$this->mailer->send_copy_of_information_request_to_customer($d["email"],$d["body"]);

			$uri = $this->card ? $this->_link_to(array("action" => "message_sent", "card_id" => $this->card)) : $this->_link_to("message_sent");
			$this->_redirect_to($uri);
		}
	}

	function message_sent(){
		$this->page_title = $this->breadcrumbs[] = _("Request for information");
	}

	function _before_filter(){
		$product = $card = null;
		if($this->params->defined("product_id") && !($product = $this->_just_find("product","product_id"))){
			return $this->_execute_action("error404");
		}
		if($this->params->defined("card_id") && !($card = $this->_just_find("card","card_id"))){
			return $this->_execute_action("error404");
		}
		if($product){ $card = $product->getCard(); }
		if(
			($card && $card->isDeleted()) || 
			($product && $product->isDeleted())
		){ return $this->_execute_action("error404"); }

		$this->product = $this->tpl_data["product"] = $product;
		$this->card = $this->tpl_data["card"] = $card;

		if($card){
			$this->_add_card_to_breadcrumbs($card);
		}
	}

	function _prepare_initial_text($card,$product){
		$this->tpl_data["hostname"] = $this->request->getHttpHost();
		$this->tpl_data["card"] = $card;
		$this->tpl_data["product"] = $product;

		return $this->_render(array("partial" => "initial_text"));
	}
}
