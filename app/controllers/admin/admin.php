<?php
require_once(__DIR__."/../application_base.php");

class AdminController extends ApplicationBaseController{
	function _application_before_filter(){
		parent::_application_before_filter();

		$this->breadcrumbs[] = array(_("Administration"), $this->_link_to(array("namespace" => "admin", "action" => "main/index")));

		if(!$this->logged_user || !$this->logged_user->isAdmin()){
			if($this->controller=="main" && $this->action=="index" && $this->request->get() && !$this->logged_user){
				// in the case that this is the main page of administration
				// we can simply redirect not-logged user to the login form
				return $this->_redirect_to(array(
					"namespace" => "",
					"action" => "logins/create_new",
					"return_uri" => $this->request->getUri(),
				));
			}

			return $this->_execute_action("error403");
		}

		$navi = new Menu14();

		foreach(array(
			array(_("Welcome screen"),			"main"),
			array(_("Articles"),						"articles"),
			array(_("Pages"),								"pages"),
			array(_("Tags"),								"tags"),
			array(_("Users"),								"users"),
			array(_("Products"),						"cards,products,card_sections,related_cards,consumables,accessories,card_filters,technical_specifications"),
			array(_("Categories"),					"category_trees,categories,category_cards"),
			array(_("Brands"),							"brands"),
			array(_("Collections"),					"collections"),
			array(_("Stores"),							"stores"),
			array(_("Password recoveries"),	"password_recoveries"),
			array(_("Newsletter subscribers"), "newsletter_subscribers"),
			array(_("404 Redirections"),				"error_redirections"),
		) as $item){
			$_label = $item[0];
			$_controllers = explode(',',$item[1]); // "products,cards" => array("products","cards");
			$_action = "$_controllers[0]/index"; // "products" -> "products/index"
			$_url = $this->_link_to($_action);
			$navi->add($_label,$_url,array("active" => in_array($this->controller,$_controllers)));
			if(in_array($this->controller,$_controllers)){
				$this->breadcrumbs[] = array($_label,$this->_link_to("$_controllers[0]/index"));
			}
		}

		$this->tpl_data["section_navigation"] = $navi;
	}

	function _before_render(){
		// auto breadcrumbs
		if($this->action!="index" && !preg_match('/^error/',$this->action)){ // error404 or error403
			$this->breadcrumbs[] = $this->page_title;
		}
		parent::_before_render();
	}

	function _add_card_to_breadcrumbs($card){
		if(!$card){ return; }
		$this->breadcrumbs[] = array($card->getName(),$this->_link_to(array("action" => "cards/edit", "id" => $card)));
	}

	function _add_category_to_breadcrumbs($category){
		if(!$category){ return; }

		$root = $category->getRootCategory();
		$this->breadcrumbs[] = array(_("Category tree"),$this->_link_to(array("action" => "category_trees/detail", "id" => $root)));

		// breadcrumbs
		$ancestors = array();
		$c = $category;
		while($p = $c->getParentCategory()){
			$ancestors[] = $p;
			$c = $p;
		}
		$ancestors = array_reverse($ancestors);
		$ancestors[] = $category;
		foreach($ancestors as $a){
			$name = $a->getName();
			if($a->isFilter()){ $name = _("filtr").": $name"; }
			if($a->isAlias()){ $name = _("alias").": $name"; }

			$this->breadcrumbs[] = array(
				$name,
				$this->_link_to(array("action" => "categories/edit", "id" => $a))
			);
		}
	}

	/**
	 * Generic method for listing objects
	 *
	 *	function index(){
	 *		$this->_index(array(
	 *			"page_title" => _("List of Authors"),
	 *			"searching_in" => "id,name,short_description",
	 *			"sorting_by" => "created_at,name,updated_at",
	 *		));
	 *	}
	 * 
	 */
	function _index($options = array()){
		$options += array(
			"page_title" => "",
			"conditions" => array(),
			"bind_ar" => array(),
			"searching_in" => "", // "id,name,description"
			"sorting_by" => "", // "id,name,created_at,updated_at"
			"class_name" => "",
		);

		if(!$options["class_name"]){
			$options["class_name"] = String4::ToObject(get_class($this))->gsub('/Controller$/','')->singularize()->toString(); // "PeopleController" -> "Person" 
		}

		if(!$options["page_title"]){
			$options["page_title"] = sprintf(_("Seznam objektů typu %s"),$options["class_name"]);
		}

		$this->page_title = $options["page_title"];

		$conditions = $options["conditions"];
		$bind_ar = $options["bind_ar"];

		($d = $this->form->validate($this->params)) || ($d = $this->form->get_initial());
		if(isset($d["search"])){
			$this->_prepare_search_conditions($options["searching_in"],$d["search"],$conditions,$bind_ar);
		}

		$this->_initialize_prepared_sorting($options["sorting_by"]);

		$this->finder = $this->_prepare_finder(array(
			"conditions" => $conditions,
			"bind_ar" => $bind_ar
		));
		
	}

	/**
	 * Generic method for creating a record
	 */
	function _create_new($options = array()){
		$options += array(
			"page_title" => "",
			"class_name" => "",
			"flash_message" => _("The record has been created"),
			"redirect_to" => null, // null, "detail", "/admin/cs/articles/", function($record){ return ... }
			"create_closure" => null, // function($d){ }
		);

		$options += array(
			"save_return_uri" => !$options["redirect_to"],
		);

		if(!$options["class_name"]){
			$options["class_name"] = String4::ToObject(get_class($this))->gsub('/Controller$/','')->singularize()->toString(); // "PeopleController" -> "Person" 
		}

		if(!$options["page_title"]){
			$options["page_title"] = sprintf(_("Vytvořit objekt typu %s"),$options["class_name"]);
		}

		$this->page_title = $options["page_title"];
		$class_name = $options["class_name"];

		$this->__set_template_name_for_action();

		$options["save_return_uri"] && $this->_save_return_uri();

		if($this->request->get()){
			$this->form->set_initial($this->params);
		}

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			if($options["create_closure"]){
				$fn = $options["create_closure"];
				$record = $fn($d);
			}else{
				$record = $class_name::CreateNewRecord($d);
			}

			if($this->form->has_errors()){
				// chyba muze byt nastavena v $options["create_closure"]
				return;
			}

			$this->flash->success($options["flash_message"]);

			if($options["redirect_to"]){
				if(is_callable($options["redirect_to"])){
					$fn = $options["redirect_to"];
					$this->_redirect_to($fn($record));
				}elseif($options["redirect_to"]=="detail"){
					$this->_redirect_to(array("action" => "detail", "id" => $record));
				}else{
					$this->_redirect_to($options["redirect_to"]);
				}
			}else{
				$this->_redirect_back();
			}

		}
	}

	/**
	 * Generic method for editing a record
	 */
	function _edit($options = array()){
		$options += array(
			"page_title" => "",
			"object" => null,
			"flash_message" => _("Změny byly uloženy"),
			"redirect_to" => null,
			"has_image_gallery" => false,
			"has_attachments" => false,
			"set_initial_closure" => null, // function($form,$object){...}
			"update_closure" => null, // function($object,$d)
		);

		$options += array(
			"save_return_uri" => !$options["redirect_to"]
		);

		$object = $options["object"];
		if(!$this->__prepare_object_for_action($object)){
			return;
		}

		if(method_exists($object,"isEditable") && !$object->isEditable()){
			return $this->_execute_action("error404");
		}

		$this->tpl_data["object"] = $object;
		$this->tpl_data["has_image_gallery"] = $options["has_image_gallery"];
		$this->tpl_data["has_attachments"] = $options["has_attachments"];

		if(!$options["page_title"]){
			$options["page_title"] = sprintf(_("Editace objektu typu %s"),get_class($object));
		}

		$this->page_title = $options["page_title"];

		$this->__set_template_name_for_action();

		if($options["set_initial_closure"]){
			$fn = $options["set_initial_closure"];
			$fn($this->form,$object);
		}else{
			$this->form->set_initial($object);
		}
		$options["save_return_uri"] && $this->_save_return_uri();

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			if($options["update_closure"]){
				$fn = $options["update_closure"];
				$fn($object,$d);
			}else{
				$object->s($d);
			}

			if($this->form->has_errors()){
				// chyba muze byt nastavena v $options["update_closure"]
				return;
			}

			$this->flash->success($options["flash_message"]);
			if($options["redirect_to"]){
				if(is_callable($options["redirect_to"])){
					$fn = $options["redirect_to"];
					$options["redirect_to"] = $fn($object);
				}
				$this->_redirect_to($options["redirect_to"]);
			}else{
				$this->_redirect_back();
			}
		}
	}

	/**
	 * Generic method for ranking a record
	 *
	 *	function set_rank(){
	 *		$this->_set_rank();
	 *		// or $this->_set_rank($this->gallery_item);
	 *	}
	 */
	function _set_rank($object = null,$options = array()){
		if(is_array($object)){
			$options = $object;
			$object = null;
		}

		$options += array(
			"prepare_object" => true,
			"set_rank_closure" => null,
		);

		if(!$this->request->post()){ return $this->_execute_action("error404"); }

		if($options["prepare_object"]){
			if(!$this->__prepare_object_for_action($object)){
				return;
			}
		}

		if($options["set_rank_closure"]){
			$fn = $options["set_rank_closure"];
			$record = $fn($object);
		}else{
			$object->setRank($this->params->getInt("rank"));
		}

		$this->render_template = false;
	}

	/**
	 * Generic method for deleting a record
	 *
	 * $this->_destroy($this->article);
	 * $this->_destroy(); // the object for deletion will be determined by the controller name
	 * $this->_destroy(array("prepare_object" => false, "destroy_closure" => function($object){ .... }));
	 */
	function _destroy($object = null, $options = array()){
		if(is_array($object)){
			$options = $object;
			$object = null;
		}

		$options += array(
			"prepare_object" => true,
			"destroy_closure" => null,
			"redirect_to" => "index", // on a non-XHR request
		);

		if(!$this->request->post()){ return $this->_execute_action("error404"); }

		if($options["prepare_object"]){
			if(!$this->__prepare_object_for_action($object)){
				return;
			}
		}

		if(method_exists($object,"isDeletable") && !$object->isDeletable()){
			return $this->_execute_action("error404");
		}

		if($options["destroy_closure"]){
			$fn = $options["destroy_closure"];
			$record = $fn($object);
		}else{
			if(!$object){
				return $this->_execute_action("error404");
			}
			$object->destroy();
		}

		$object && $this->logger->info(sprintf("user $this->logged_user just deleted %s#%d",get_class($object),$object->getId()));

		$this->__set_template_name_for_action();

		if(!$this->request->xhr()){
			$this->flash->success(_("The entry has been deleted"));
			$this->_redirect_to($options["redirect_to"]);
		}
	}

	/**
	 * Compiles search conditions for SQL query with LIKE operator
	 *
	 * $q = "John Rambo";
	 * $this->_prepare_search_conditions("id,name,description",$q,$conditions,$bind_ar);
	 *
	 * $finder = Person::Finder(array(
	 *	"conditions" => $conditions,
	 *	"bind_ar" => $bind_ar,
	 * ));
	 */
	function _prepare_search_conditions($fields,$q,&$conditions,&$bind_ar){
		if(is_string($fields)){
			$fields = explode(",",$fields); // "id,name" -> array("id","name")
		}
		if(!isset($conditions)){ $conditions = array(); }
		if(!isset($bind_ar)){ $bind_ar = array(); }

		$q = trim($q);
		if(!$q){ return; }

		$unaccent_installed = $this->dbmole->selectInt("SELECT COUNT(*) FROM pg_extension WHERE extname=:extname",array(":extname" => "unaccent"));

		$q = Translate::Lower($q);
		$fields = "LOWER(COALESCE(''||".join(",'')||' '||COALESCE(''||",$fields).",''))";
		if($unaccent_installed && Translate::CheckEncoding($q,"ASCII")){
			$fields = "UNACCENT($fields)";
		}

		($cond = FullTextSearchQueryLike::GetQuery($fields,$q,$bind_ar)) ||
		($cond = "'invalid'='search_query'"); // it causes that nothing will be found

		$conditions[] = $cond;

		return $cond;
	}

	/**
	 * Adds into the $this->sorting prearranged sorting schemas
	 *
	 * $this->_initialize_prepared_sorting("name");
	 * $this->_initialize_prepared_sorting("name,created_at,updated_at");
	 */
	function _initialize_prepared_sorting($keys){
		if(is_string($keys)){
			if(strlen($keys)==0){ return; }
			$keys = explode(",",$keys);
		}

		foreach($keys as $key){
			switch($key){
				case "created_at":
					$this->sorting->add("created_at",array("reverse" => true));
					break;
				case "login":
				case "name":
				case "title":
					$this->sorting->add("$key","UPPER($key), $key, id DESC","UPPER($key) DESC, $key DESC, id ASC");
					break;
				case "updated_at":
					$this->sorting->add("updated_at","COALESCE(updated_at,'2000-01-01') DESC, id DESC","COALESCE(updated_at,'2000-01-01') ASC, id ASC");
					break;
				case "published_at":
					$this->sorting->add("published_at",array("reverse" => true));
					break;
				case "is_admin":
					$this->sorting->add("is_admin","is_admin DESC, UPPER(login)","is_admin ASC, UPPER(login)");
					break;
				default:
					throw new Exception("Unknown key for sorting: $key");
			}
		}
	}

	/**
	 * Creates a finder for the given class with specific conditions
	 */
	function _prepare_finder($options = array()){
		$options += array(
			"conditions" => array(),
			"bind_ar" => array(),
			"class_name" => null,
			"limit" => 20,
		);

		if(!$options["class_name"]){
			$options["class_name"] = String4::ToObject(get_class($this))->gsub('/Controller$/','')->singularize()->toString(); // "PeopleController" -> "Person" 
		}

		$class_name = $options["class_name"];

		$this->tpl_data["finder"] = $finder = $class_name::Finder(array(
			"conditions" => $options["conditions"],
			"bind_ar" => $options["bind_ar"],
			"offset" => $this->params->getInt("offset"),
			"order_by" => $this->sorting,
			"limit" => $options["limit"],
			"use_cache" => true,
		));

		return $finder;
	}
}
