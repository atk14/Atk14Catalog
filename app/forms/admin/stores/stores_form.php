<?php
class StoresForm extends AdminForm {

	function set_up()	{
		$this->add_translatable_field("name", new CharField(array(
			"label" => _("Name"),
		)));

		$this->add_translatable_field("teaser", new CharField(array(
			"label" => _("Brief description"),
			"required" => false,
		)));

		$this->add_field("image_url", new PupiqImageField(array(
			"label" => _("Image"),
			"required" => false,
		)));

		$this->add_field("email", new EmailField(array(
			"label" => _("Email"),
			"max_length" => 255,
			"required" => false,
		)));

		$this->add_field("phone", new PhoneField(array(
			"label" => _("Phone"),
			"required" => false,
		)));

		$this->add_translatable_field("address", new TextField(array(
			"label" => _("Address"),
			"required" => false,
		)));

		$this->add_field("location_lat", new FloatField([
			"label" => _("Latitude"),
			"hint" => "50.0876229",
			"required" => false,
		]));

		$this->add_field("location_lng", new FloatField([
			"label" => _("Longitude"),
			"hint" => "14.4639075",
			"required" => false,
		]));

		$this->add_translatable_field("opening_hours", new TextField(array(
			"label" => _("Opening hours"),
			"required" => false,
		)));

		$this->add_translatable_field("description", new MarkdownField(array(
			"label" => _("Description"),
			"required" => false,
		)));
	}

	function clean(){
		list($err,$d) = parent::clean();

		if(array_key_exists("location_lat",$d) && array_key_exists("location_lng",$d)){
			if((is_null($d["location_lat"]) + is_null($d["location_lng"]))==1){ // true + false or false + true -> 1
				$this->set_error(_("Either fill out Latitude and Longitude or leave them blank."));
			}
		}

		return array($err,$d);
	}
}
