<?php
/**
 * Z {link_to action="categories/detail" path="nabytek/detsky-nabytek"}
 * udela
 * /nabytek/detsky-nabytek/
 */
class CategoriesRouter extends Atk14Router{

	function recognize($uri){
		if($this->namespace=="" && preg_match('/^\/([a-z0-9-_\/]+?)\/?$/',$uri,$matches) && ($category = Category::GetInstanceByPath($matches[1],$lang))){
			$this->action = "detail";
			$this->controller = "categories";
			$this->params["path"] = $matches[1];
			$this->lang = $lang;
		}
	}

	function build(){
		global $ATK14_GLOBAL;

		if($this->namespace!="" || $this->controller!="categories" || $this->action!="detail" || !$this->params["path"]){ return; }

		$path = $this->params["path"];
		unset($this->params["path"]);

		// $path may vary when the $this->lang changes
		$l = null;
		$category = Category::GetInstanceByPath($path,$l);
		if($category && ($l!=$this->lang)){
			$path_ar = array();
			foreach(Category::GetInstancesOnPath($path) as $c){
				$path_ar[] = $c->getSlug($this->lang);
			}
			$path = join("/",$path_ar);
		}

		$uri = "/$path/";
		
		return $uri;
	}
}
