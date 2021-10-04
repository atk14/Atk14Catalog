<?php
class FixSlugCollisionForm extends AdminForm {

	function set_up(){
		$this->add_translatable_field("name", new CharField([
			"label" => _("Name"),
		]));

		$this->add_slug_field();
	}

	function clean(){
		list($err,$d) = parent::clean();
		$this->detect_collision_slug($d,$this->controller->parent_category);
		return [$err,$d];
	}

	function detect_collision_slug($d,$parent_category){
		global $ATK14_GLOBAL;

		if(!is_array($d)){ return; }

		$parent_category_id = TableRecord::ObjToId($parent_category);
		foreach($ATK14_GLOBAL->getSupportedLangs() as $l){
			if(!isset($d["slug_$l"])){ continue; }
			if(Category::GetInstanceBySlug($d["slug_$l"],$l,"$parent_category_id")){
				$this->set_error("slug_$l",_("The same slug already exists at this place"));
			}
		}
	}
}
