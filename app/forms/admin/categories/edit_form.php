<?php
class EditForm extends CategoriesForm{

	function set_up(){
		$category = $this->controller->category;
		$this->_add_fields(array(
			"add_slug_field" => true,
			"add_page_title_and_description_fields" => !$category->isFilter() && !$category->isAlias(),
		));
	}

	function clean(){
		list($err,$d) = parent::clean();

		$this->_clean_slugs($d);

		return array($err,$d);
	}
}
