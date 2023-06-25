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

		$this->add_field("phone",new PhoneField(array(
			"label" => _("Phone"),
			"required" => false,
		)));

		$this->add_sign_up_for_newsletter_field((array(
			"initial" => true,
		)));

		$this->add_field("body",new TextField(array(
			"label" => _("Text"),
			"max_length" => 2000,
		)));

		$this->enable_csrf_protection();
		$this->set_button_text(_("Send message"));
	}
}
