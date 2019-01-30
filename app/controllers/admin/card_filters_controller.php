<?php
/**
 * Rychle zarazovani daneho produktu do filtracnich kategorii.
 */
class CardFiltersController extends AdminController{

	function edit(){
		$card = $this->card;
		$this->page_title = sprintf(_('Placing the product "%s" into filters'),$card->getName());
	
		$ids = array();
		$filters = array();
		foreach($card->getCategories() as $c){
			foreach($c->getAvailableFilters() as $f){
				if(in_array($f->getId(),$ids)){ continue; }
				$filters[] = $f;
				$ids[] = $f->getId();
			}
		}

		$current_category_ids = $card->getCategoryIds();

		$sections = array();
		$available_category_ids = array();
		foreach($filters as $f){
			$_fields = array();

			foreach($f->getChildCategories() as $c){
				$this->form->add_field("category_".$c->getId(),new BooleanField(array(
					"label" => strip_tags($c->getName()),
					"initial" => in_array($c->getId(),$current_category_ids),
					"required" => false,
				)));
				$_fields[] = "category_".$c->getId();
				$available_category_ids[] = $c->getId();
			}

			if($_fields){
				$sections[] = array(
					"legend" => "/".$f->getPath()."/",
					"fields" => $_fields
				);
			}
		}

		if(!$sections){
			$this->_execute_action("no_filter_categories");
			return;
		}

		$this->tpl_data["sections"] = $sections;

		$this->_save_return_uri();

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			foreach($d as $key => $value){
				if(!preg_match('/^category_(\d+)$/',$key,$matches)){ continue; }
				$c_id = $matches[1];

				$value && $card->addToCategory($c_id);
				!$value && $card->removeFromCategory($c_id);

				$this->flash->success(_("Settings saved"));

				$this->_redirect_back();
			}
		}
	}

	function no_filter_categories(){
		$this->page_title = sprintf(_('Placing the product "%s" into filters'),$this->card->getName());
	}

	function _before_filter(){
		$card = $this->_find("card");

		if ($card) {
			if ($card->isDeleted()){
				return $this->_execute_action("error404");
 			}
			$this->_add_card_to_breadcrumbs($card);
		}
	}

}
