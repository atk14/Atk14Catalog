<?php
/**
 * The application's mailer class.
 *
 * In DEVELOPMENT emails are not sent for real,
 * they are logged to the application's log instead.
 *
 * Within a controller one can send an email like this:
 *     $this->mailer->execute("notify_user_registration",$user);
 *
 * Be aware of the fact that $this->mailer is a member of Atk14MailerProxy,
 * so you can use it this way
 *     $this->mailer->notify_user_registration($user);
 */
class ApplicationMailer extends Atk14Mailer {

	/**
	 * A place for common configuration.
	 */
	function _before_filter(){
		$this->content_type = "text/plain";
		$this->content_charset = DEFAULT_CHARSET; // "UTF-8"
		$this->from = DEFAULT_EMAIL;
		// $this->bcc = "";
	}

	function notify_user_registration($user){
		$this->tpl_data["user"] = $user;
		$this->to = $user->getEmail();
		$this->subject = sprintf(_("Welcome to %s"),ATK14_APPLICATION_NAME);
		// body is rendered from app/views/mailer/notify_user_registration.tpl
	}

	function notify_password_recovery($password_recovery){
		$this->tpl_data["user"] = $user = $password_recovery->getUser();
		$this->tpl_data["password_recovery"] = $password_recovery;

		$this->to = $user->getEmail();
		$this->subject = _("Reset Your Password");
		// body is rendered from app/views/mailer/notify_password_recovery.tpl
	}

	function notify_password_update_in_recovery($password_recovery){
		$this->tpl_data["user"] = $user = $password_recovery->getUser();
		$this->tpl_data["password_recovery"] = $password_recovery;

		$this->to = $user->getEmail();
		$this->subject = _("Your password was updated");
	}

	/**
	 *	$mailer->contact_message(array(
	 *		"name" => "John Doe",
	 *		"email" => "john@doe.com",
	 *		"body" => "Hi, I just lost my password..."
	 *	),$request->getRemoteAddr(),$logged_user);
	 */
	function contact_message($params,$remote_addr,$logged_user){
		$this->to = DEFAULT_EMAIL;
		$this->subject = _("Message sent from contact page");
		$this->tpl_data += $params;
		$this->tpl_data["remote_addr"] = $remote_addr;
		$this->tpl_data["logged_user"] = $logged_user;
		$this->render_layout = false;
	}

	function send_information_request($params,$remote_addr,$logged_user){
		$this->to = DEFAULT_EMAIL;
		$this->subject = _("Request for information");
		$this->tpl_data += $params;
		$this->tpl_data["remote_addr"] = $remote_addr;
		$this->tpl_data["logged_user"] = $logged_user;
		$this->render_layout = false;
	}

	function send_copy_of_information_request_to_customer($email,$text){
		$this->to = $email;
		$this->subject = _("Request for information");
		$this->tpl_data["text"] = $text;
	}
}
