<?php
/**
 *
 * In a controller just before an object updating (in action edit):
 *
 *	$this->_save_slug_state($this->article);
 *
 * After the successful updating:
 *
 *	$this->flash->success("Article successfully updated");
 *	$this->_redirect_back_or_edit_slug();
 *
 */
trait TraitSlugStateWatcher {

	var $slug_state_watching_object;
	var $original_patterns;
	var $original_slugs;

	function _save_slug_state($object){
		$this->slug_state_watching_object = $object;
		list($this->original_patterns,$this->original_slugs) = $this->_get_patterns_and_slugs($object);
	}

	function _redirect_back_or_edit_slug($options = []){
		if(is_object($options)){
			$options = ["object" => $options];
		}
		if(is_string($options)){
			$options = ["message" => $options];
		}
		$options += [
			"object" => $this->slug_state_watching_object,
			"message" => null,
		];

		$object = $options["object"];

		if(is_null($options["message"])){
			$options["message"] = (string)$this->flash->getMessage("success",["set_read_state" => false]);
		}

		list($new_patterns,$new_slugs) = $this->_get_patterns_and_slugs($options["object"]);

		if($new_patterns!=$this->original_patterns && $new_slugs==$this->original_slugs){
			$this->flash->success($options["message"]."<br><br>"._("Please make sure you don't need to change slugs"));

			$this->_redirect_to(array("action" => "slugs/edit", "table_name" => $object->getTableName(), "record_id" => $object, "return_uri" => $this->_get_return_uri()));
			return;
		}

		$this->flash->success($options["message"]);
		$this->_redirect_back();
	}

	function _get_patterns_and_slugs($object){
		global $ATK14_GLOBAL;

		$patterns = $slugs = array();
		foreach($ATK14_GLOBAL->getSupportedLangs() as $l){
			$patterns[$l] = $object->getSlugPattern($l);
			$slugs[$l] = $object->getSlug($l);
		}

		return array($patterns,$slugs);
	}
}
