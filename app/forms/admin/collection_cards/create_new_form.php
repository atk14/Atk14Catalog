<?php
class CreateNewForm extends AdminForm {
	function set_up() {
		$this->add_field("card_id", new CardField());
	}

	function clean() {
		$d = &$this->cleaned_data;
		if (isset($d["card_id"])) {
			if ($col = $d["card_id"]->getCollection()) {
				if ($col->getId() == $this->controller->collection->getId()) {
					$this->set_error("card_id", _("Tento produkt už je součástí této kolekce"));
				} else {
					$this->set_error("card_id", sprintf(_("Tento produkt už je součástí jiné kolekce [%s]"),$col));
				}

			}
		}
		return array(null, $d);
	}
}
