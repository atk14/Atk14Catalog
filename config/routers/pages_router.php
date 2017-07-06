<?php
/**
 * Z {link_to controller="pages" action="detail" id="4"}
 * udela
 * /zvitezili-jsme-v-soutezi-prodejna-roku-2012/
 */
class PagesRouter extends Atk14Router{
	function recognize($uri){
		if($this->namespace=="" && preg_match('/^\/([a-z0-9-_\/]+?)\/?$/',$uri,$matches) && ($sp = Page::GetInstanceByPath($matches[1],$lang))){
			$this->action = "detail";
			$this->controller = "pages";
			$this->params["id"] = $sp->getId();
			$this->lang = $lang;
		}
	}

	function build(){
		if($this->namespace!="" || $this->controller!="pages" || $this->action!="detail"){ return; }

		if($sp = Cache::Get("Page",$this->params->getInt("id"))){
    	$this->params->del("id");
    	return sprintf('/%s/',$sp->getPath($this->lang));
		}
	}
}
