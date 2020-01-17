<?php
class SlugsController extends AdminController {

	function edit(){
		global $ATK14_GLOBAL;

		$this->page_title = sprintf(_('Editing slug of object "%s"'),strip_tags($this->object));

		$this->form->set_initial($this->object);
		$this->_save_return_uri();

		foreach($ATK14_GLOBAL->getSupportedLangs() as $l){
			$this->form->fields["slug_$l"]->help_text = sprintf('Pattern: %s',h($this->object->getSlugPattern($l)));
		}

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			if($this->form->changed()){
				$this->object->s($d,["reconstruct_missing_slugs" => true]);
				$this->flash->success(_("Changes have been saved"));
			}
			$this->_redirect_back();
		}
	}

	function _before_filter(){
		$table_name = $this->params->getString("table_name");
		$record_id = $this->params->getInt("record_id");
		if(!$table_name || !$record_id){ return $this->_execute_action("error404"); }

		$count = $this->dbmole->selectInt("SELECT COUNT(*) FROM slugs WHERE table_name=:table_name AND record_id=:record_id",[":table_name" => $table_name, ":record_id" => $record_id]);
		if(!$count){ return $this->_execute_action("error404"); }

		$class_name = String4::ToObject($table_name)
			->gsub('/^(.*?)\./','') // "public.images" -> "images"
			->singularize() // images -> image
			->camelize() // image -> Image
			->toString();
		
		$this->object = $this->tpl_data["object"] = $class_name::GetInstanceById($record_id);
		if(!$this->object){ return $this->_execute_action("error404"); }

		if(!is_a($this->object,"iSlug")){ return $this->_execute_action("error404"); }
	}
}
