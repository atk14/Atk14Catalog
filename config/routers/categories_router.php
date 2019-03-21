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
		$category = Category::GetInstanceByPath($path);
		if($category){
			$path = $category->getPath($this->lang);
		}

		$uri = "/$path/";
		
		return $uri;
	}
}
