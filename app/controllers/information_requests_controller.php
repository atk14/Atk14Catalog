<?php
class InformationRequestsController extends ApplicationController{

	function create_new(){
		$this->page_title = _("Request for information");

		$this->_save_return_uri();

		if($this->request->get()){
			$product = $card = null;
			if($product = Product::FindById($this->params->getInt("product_id"))){
				$card = $product->getCard();
			}else{
				$card = Card::FindById($this->params->getInt("card_id"));
			}
			if(
				($card && ($card->isDeleted() || !$card->isVisible())) || 
				($product && ($product->isDeleted() || !$product->isVisible()))
			){ return $this->_execute_action("error404"); }

			$this->form->set_initial("body",$this->_prepare_initial_text($card,$product));
		}

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			if($d["sign_up_for_newsletter"] && $d["email"]){
				NewsletterSubscriber::SignUp($d["email"],array(
					"name" => $d["name"],
				));
			}

			$this->mailer->send_information_request($d,$this->request->getRemoteAddr(),$this->logged_user);
			$this->mailer->send_copy_of_information_request_to_customer($d["email"],$d["body"]);

			$this->_redirect_to("message_sent");
		}
	}

	function message_sent(){
		$this->page_title = _("Request for information");
	}

	function _prepare_initial_text($card,$product){
		$this->tpl_data["hostname"] = $this->request->getHttpHost();
		$this->tpl_data["card"] = $card;
		$this->tpl_data["product"] = $product;

		return $this->_render(array("partial" => "initial_text"));
	}
}
