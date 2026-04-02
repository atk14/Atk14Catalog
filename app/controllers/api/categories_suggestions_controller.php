<?php
class CategoriesSuggestionsController extends ApiController{

	/**
	 * Našeptávání kategorií
	 *
	 * Kategorie se vyhledávají podle cesty. Cesta je toto:
	 *
	 *     /nabytek/kresla/
	 *     /mistnosti/jidelna/stoly/jidelni-stoly/
	 *
	 * Lze zadávat toto:
	 *
	 *     Křesla
	 *     /nabytek/j
	 *     /
	 *     
	 */
	function index(){
		global $ATK14_GLOBAL;

		if(!$this->params->isEmpty() && ($d = $this->form->validate($this->params))){
			$d["q"] = new String4($d["q"]);
			$q = $d["q"]->copy();
			$parent_path = $parent = null;

			if($q->match('/^(\/.*?)([^\/]*?)$/',$matches)){
				$parent_path = $matches[1]->gsub('/^\//','')->gsub('/\/$/','');
				$q = $matches[2];

				// "/nabytek/jidelna" -> "/nabytek/jidelna/"
				if($p = Category::GetInstanceByPath("$parent_path/$q")){
					$parent_path = $p->getPath(); // "nabytek/jidelna"
					$q = new String4("");
				}
			}
			$conditions = $bind_ar = array();

			if((string)$parent_path){
				$parent = Category::GetInstanceByPath($parent_path);
				if(!$parent){
					$this->api_data = array();
					return;
				}

				$conditions[] = "parent_category_id=:parent";
				$bind_ar[":parent"] = $parent;
			}elseif($d["q"]->match('/^\//')){ // tady kontrolujeme puvodni vstup a nikoli $q
				$conditions[] = "parent_category_id IS NULL";
			}

			if((string)$q){
				$left_match = $parent ? "" : "'%'||";
				$conditions[] = "id IN (SELECT record_id FROM slugs WHERE table_name='categories' AND lang=:lang AND slug LIKE $left_match:slug||'%')";
				$bind_ar[":slug"] = (string)$q->toSlug();
				$bind_ar[":lang"] = $this->lang;
			}

			// nechceme naseptavat filtry - to je zamer!
			$conditions[] = "is_filter='f'";
			$conditions[] = "parent_category_id IS NULL OR (SELECT q.is_filter FROM categories q WHERE q.id=categories.parent_category_id)='f'";

			$categories = Category::FindAll(array(
				"conditions" => $conditions,
				"bind_ar" => $bind_ar,
			));

			$this->api_data = array();
			foreach($categories as $c){
				$trailing_slash = $c->hasChildCategories() ? "" : "/"; // pokud ma nejake potomky, netiskneme na konci lomitko 
				$this->api_data[] = "/".$c->getPath().$trailing_slash;
			}
		}
	}
}
