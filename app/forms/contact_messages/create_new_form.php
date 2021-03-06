<?php
class CreateNewForm extends ApplicationForm{

	function set_up(){
		$this->add_field("name",new CharField(array(
			"label" => _("Your name"),
			"max_length" => 200,
		)));

		$this->add_field("email",new EmailField(array(
			"label" => _("Your email"),
			"max_length" => 200,
		)));

		$this->add_field("sign_up_for_newsletter",new BooleanField(array(
			"label" => _("Sign up for newsletter"),
			"required" => false,
			"initial" => false,
		)));

		$this->add_field("body",new TextField(array(
			"label" => _("Text"),
			"max_length" => 2000,
		)));

		if(defined("RECAPTCHA_SITE_KEY") && defined("RECAPTCHA_SECRET_KEY")){
			$this->add_field("captcha",new RecaptchaField(array(
				"label" => _("Spam protection"),
			)));
		}

		$this->enable_csrf_protection();
		$this->set_button_text(_("Send message"));
	}

	function clean(){
		list($err,$values) = parent::clean();

		// perhaps you may not want to have "captcha" in the cleaned data
		if(is_array($values)){ unset($values["captcha"]); }

		return array($err,$values);
	}
}
