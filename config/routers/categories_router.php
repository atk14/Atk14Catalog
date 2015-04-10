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
		if($this->namespace!="" || $this->controller!="categories" || $this->action!="detail" || !$this->params["path"]){ return; }

		$uri = "/".$this->params["path"]."/";
		unset($this->params["path"]);
		
		return $uri;
	}
}
