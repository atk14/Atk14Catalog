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
 *		var $url_patterns_by_lang = array("en" => "article", "cs" => "clanek");
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
	var $url_patterns_by_lang = array(); // .e.g., array("en" => "article"), both keys and values must be unique
	var $model_class_name = null; // .e.g., "Article", by default it is determined automatically
	var $target_controller_name = null; // .e.g, "articles", by default it is determined automatically
	
	function __construct(){
		global $ATK14_GLOBAL;

		$cn = new String4(get_class($this)); // "ArticlesRouter"
		$cn = $cn->gsub('/Router$/',''); // "Articles"

		if(is_null($this->model_class_name)){
			$this->model_class_name = (string)$cn->singularize(); // "Article"
		}

		if(is_null($this->target_controller_name)){
			$this->target_controller_name = (string)$cn->underscore(); // "articles"
		}

		if(!$this->url_patterns_by_lang){
			$lang = $ATK14_GLOBAL->getDefaultLang();
			$this->url_patterns_by_lang = array(
				"$lang" => (string)$cn->underscore()->singularize()->replace("_","-") // "en" => "article"
			);
		}

		parent::__construct();
	}

	function recognize($uri){
		$patterns = $this->url_patterns_by_lang;
		$patterns_rev = array_combine(array_values($patterns),array_keys($patterns));
		if(sizeof($patterns)!=sizeof($patterns_rev)){
			throw new Exception(get_class($this).': non-unique values in $url_patterns_by_lang');
		}

		// TODO: prepsat pomoci array_walk?
		foreach($patterns as $k => $v){
			$patterns[$k] = str_replace('/',"\\/",$v);
		}

		$patterns = join("|",$patterns);
		$class = $this->model_class_name;
		if($this->namespace=="" && preg_match('/^\/('.$patterns.')\/([a-z0-9-_\/]+?)\/?$/',$uri,$matches)){
			$lang = $patterns_rev[$matches[1]];
			if($c = $class::GetInstanceBySlug($matches[2],$lang)){
				$this->action = "detail";
				$this->controller = $this->target_controller_name;
				$this->params["id"] = $c->getId();
				$this->lang = $lang;
			}
		}
	}

	function build(){
		if($this->namespace!="" || $this->controller!=$this->target_controller_name || $this->action!="detail" || !isset($this->url_patterns_by_lang[$this->lang])){ return; }

		$class = $this->model_class_name;
		if($c = Cache::Get($class,$this->params->getInt("id"))){
			$this->params->del("id");
			$pattern = $this->url_patterns_by_lang[$this->lang];
			return sprintf('/%s/%s/',$pattern,$c->getSlug($this->lang));
		}
	}
}
