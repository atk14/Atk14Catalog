<?php
class CardField extends CharField{
	function __construct($options = array()){
		$options += array(
			"null_empty_output" => true,
			"help_text" => _("Zadejte id produktové karty nebo katalogové číslo varianty"),
			"hints" => array(
				"123",
				"1234/4567890",
				"http://stockist.cz/produkt/zlute-houpaci-kreslo-123/"
			),
		);

		parent::__construct($options);

		$this->update_messages(array(
			"no_such_card" => _("Nepodařilo se vyhledat tento produkt")
		));
	}

	function clean($value){
		list($err,$value) = parent::clean($value);
		if($err || !$value){ return array($err,$value); }

		// http://stockist.cz/produkt/zlute-houpaci-kreslo-123/ -> 123
		if(preg_match('/^https?:\/\/.*?(\d+)\/(|\?.*)$/',$value,$matches)){
			$value = $matches[1];
		}

		if($product = Product::FindByCatalogId($value)){
			return array(null,$product->getCard());
		}

		if(is_numeric($value)){
			if(!$card = Card::FindById($value)){
				return array($this->messages["no_such_card"],null);
			}
			return array(null,$card);
		}

		return array($this->messages["no_such_card"],null);
	}
}
