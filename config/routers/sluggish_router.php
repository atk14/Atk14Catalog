<?php
/**
 * Base router for routers rendering URLs with slugs
 *
 * It can handle "detail" URLs.
 * For the moment it is usable only in the default namespace ("").
 *
 * Usage:
 *
 * Router class
 *	// file: config/routers/articles_router.php
 * 	class ArticlesRouter extends SluggishRouter{
 *		var $url_sections_by_lang = array("en" => "article", "cs" => "clanek");
 * 	}
 *
 * Enable the router
 *	// file: config/routers/load.php
 *	Atk14Url::AddRouter("ArticlesRouter");
 *
 * In a template
 * 	{a controller="articles" action="detail" id=123}Here is the article{/a}
 *
 * Rendered HTML
 * 	<a href="/article/why-is-the-atk14-so-cool/">Here is the article</a>
 *
 */
class SluggishRouter extends Atk14Router{
	var $url_sections_by_lang = array(); // .e.g., array("en" => "article"), both keys and values must be unique
	var $model_class_name = null; // .e.g., "Article", by default it is determined automatically
	var $target_controller_name = null; // .e.g, "articles", by default it is determined automatically
	
	function __construct(){
		global $ATK14_GLOBAL;

		$cn = new String(get_class($this)); // "ArticlesRouter"
		$cn = $cn->gsub('/Router$/',''); // "Articles"

		if(is_null($this->model_class_name)){
			$this->model_class_name = (string)$cn->singularize(); // "Article"
		}

		if(is_null($this->target_controller_name)){
			$this->target_controller_name = (string)$cn->underscore(); // "articles"
		}

		if(!$this->url_sections_by_lang){
			$lang = $ATK14_GLOBAL->getDefaultLang();
			$this->url_sections_by_lang = array(
				"$lang" => (string)$cn->underscore()->singularize()->replace("_","-") // "en" => "article"
			);
		}

		parent::__construct();
	}

	function recognize($uri){
		$sections = join("|",$this->url_sections_by_lang);
		$class = $this->model_class_name;
		if($this->namespace=="" && preg_match('/^\/('.$sections.')\/([a-z0-9-_\/]+?)\/?$/',$uri,$matches) && ($c = $class::GetInstanceBySlug($matches[2],$lang))){
			$this->action = "detail";
			$this->controller = $this->target_controller_name;
			$this->params["id"] = $c->getId();
			foreach($this->url_sections_by_lang as $l => $s){
				if($s==$matches[1]){
					$this->lang = $l;
				}
			}
		}
	}

	function build(){
		if($this->namespace!="" || $this->controller!=$this->target_controller_name || $this->action!="detail" || !isset($this->url_sections_by_lang[$this->lang])){ return; }

		$class = $this->model_class_name;
		if($c = Cache::Get($class,$this->params->getInt("id"))){
			$this->params->del("id");
			$section = $this->url_sections_by_lang[$this->lang];
			return sprintf('/%s/%s/',$section,$c->getSlug($this->lang));
		}
	}
}
