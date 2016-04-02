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
				"limit" => 20,
			));

			$this->api_data = array();
			foreach($categories as $c){
				$trailing_slash = $c->hasChildCategories() ? "" : "/"; // pokud ma nejake potomky, netiskneme na konci lomitko 
				$this->api_data[] = "/".$c->getPath().$trailing_slash;
			}
		}
	}

	/*
	// Mysakova puvodni funkce
	//function index(){
		global $ATK14_GLOBAL;

		if(!$this->params->isEmpty() && ($d = $this->form->validate($this->params))){
			$this->api_data = array();

			preg_match("/([^\/]+)$/", $d["q"], $m);
			$levels = preg_split("/\//", $d["q"]);
			if (sizeof($levels)==0)
				return;

			if ($m) {
				$q = $m[1];
			} else {
				$q = array_pop($levels);
				$q = array_pop($levels);
			}
			$categories = Category::FindAll(array(
				"conditions" => "id IN (SELECT record_id FROM translations WHERE table_name='categories' AND key='name' AND lang=:lang AND lower(body) LIKE lower(:q))",
				"bind_ar" => array(":q" => "%$q%", ":lang" => $ATK14_GLOBAL->getLang()),
				#"order_by" => "LOWER(tag), tag",
				"limit" => 20,
			));
			foreach($categories as $c){
				$name_path = new String4($c->getNamePath());
				$name_path = new String4($name_path->lower());
				$slug_path = new String4($c->getPath());
				$slug_path = new String4($slug_path->lower());
				$q = new String4($d["q"]);
				$q = new String4($q->lower());

				$levels = preg_split("/\//", $d["q"]);
				if (sizeof($levels==1) or ((sizeof($levels>1)) && (mb_strpos("$name_path","$q")===0 || mb_strpos("$slug_path","$q")===0 ))) {
					$this->api_data[] = $c->getNamePath();
					if (sizeof($levels)>1 && !$m) {
						foreach($c->getChildCategories() as $cc) {
							$this->api_data[] = $cc->getNamePath();
							if (sizeof($this->api_data)>=20) break;
						}
					}
							if (sizeof($this->api_data)>=20) break;
				}
			}
		}
	}*/
}
