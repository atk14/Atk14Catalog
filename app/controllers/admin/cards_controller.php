<?php
require_once(__DIR__ . "/trait_slug_state_watcher.php");

class CardsController extends AdminController{

	use TraitSlugStateWatcher;

	function index(){
		$this->page_title = _("List of Products");

		($d = $this->form->validate($this->params)) || ($d = $this->form->get_initial());

		$conditions = $bind_ar = array();
		$conditions = array("deleted='f'");

		if(isset($d["visible"])){
			$conditions[] = "visible=:visible";
			$bind_ar[":visible"] = !!$d["visible"];
		}

		$q = $d["search"];
		$q_up = Translate::Upper($q);

		if($ft_cond = FullTextSearchQueryLike::GetQuery("UPPER(".join("||' '||",array(
				"id",
				"COALESCE((SELECT body FROM translations WHERE record_id=cards.id AND table_name='cards' AND key='name' AND lang=:lang),'')",
				"COALESCE((SELECT body FROM translations WHERE record_id=cards.id AND table_name='cards' AND key='teaser' AND lang=:lang),'')",
			)).")",$q_up,$bind_ar)
		){
			$bind_ar[":lang"] = $this->lang;
			$ft_cond = array($ft_cond);

			$ft_cond[] = "cards.id IN (SELECT card_id FROM products WHERE ".FullTextSearchQueryLike::GetQuery("UPPER(".join("||' '||",array(
				"catalog_id",
				"id::VARCHAR",
				"COALESCE((SELECT body FROM translations WHERE record_id=products.id AND table_name='products' AND key='name' AND lang=:lang),'')",
				"COALESCE((SELECT body FROM translations WHERE record_id=products.id AND table_name='products' AND key='label' AND lang=:lang),'')",
				"COALESCE((SELECT body FROM translations WHERE record_id=products.id AND table_name='products' AND key='description' AND lang=:lang),'')",
			)).")",$q_up,$bind_ar).")";

			$ft_cond[] = "cards.id IN (SELECT card_id FROM card_sections WHERE ".FullTextSearchQueryLike::GetQuery("UPPER(".join("||' '||",array(
				"COALESCE((SELECT body FROM translations WHERE record_id=card_sections.id AND table_name='card_sections' AND key='name' AND lang=:lang),'')",
				"COALESCE((SELECT body FROM translations WHERE record_id=card_sections.id AND table_name='card_sections' AND key='body' AND lang=:lang),'')",
			)).")",$q_up,$bind_ar).")";

			// a special sorting in searching
			$name = "COALESCE((SELECT body FROM translations WHERE record_id=cards.id AND table_name='cards' AND key='name' AND lang=:lang),'')";
			$conditions[] = '('.join(') OR (',$ft_cond).')';
			$this->sorting->add("search","
				cards.id::VARCHAR=:search DESC,
				cards.id::VARCHAR LIKE :search||'%' DESC,
				UPPER($name) LIKE UPPER(:search||'%') DESC,
				created_at DESC
			");
			$bind_ar[":search"] = $q;
		}

		$this->_initialize_prepared_sorting("created_at");
		$this->sorting->add("id");
		$this->sorting->add("name", array("order_by" => Translation::BuildOrderSqlForTranslatableField("cards", "name")));
		$this->sorting->add("updated_at","COALESCE(updated_at,'2000-01-01') DESC, created_at DESC, id DESC","COALESCE(updated_at,'2099-01-01'), created_at, id");
		$this->sorting->add("has_variants");
		$this->sorting->add("visible","visible DESC, created_at DESC","visible ASC, created_at DESC");

		$this->tpl_data["finder"] = Card::Finder(array(
			"conditions" => $conditions,
			"bind_ar" => $bind_ar,
			"order_by" => $this->sorting,
			"offset" => $this->params->getInt("offset"),
		));
	}

	function create_new() {
		$this->page_title = _("Adding a product");

		$this->_save_return_uri();

		if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			$tags = $d["tags"];
			unset($d["tags"]);

			$section_data = array();
			foreach($GLOBALS["ATK14_GLOBAL"]->getSupportedLangs() as $l){
				$k = "information_$l";
				if(strlen(trim($d[$k]))){
					$section_data["body_$l"] = $d[$k];
				}
				unset($d[$k]);
			}

			$catalog_id = $d["catalog_id"];
			unset($d["catalog_id"]);

			$card = Card::CreateNewRecord($d);
			$card->setTags($tags);
			if(strlen($catalog_id)){
				$card->createProduct(array(
					"catalog_id" => $catalog_id,
				));
			}

			if($section_data){
				$section_data["card_id"] = $card;
				$section_data["card_section_type_id"] = CardSectionType::FindByCode("info");
				CardSection::CreateNewRecord($section_data);
			}

			$this->flash->success(_("The product has been created. Now you can add some extra data to it."));
			$this->_redirect_to(array("action" => "edit", "id" => $card, "_return_uri_" => $this->_get_return_uri()));
		}
	}

	function edit(){
		$this->page_title = sprintf(_("Editing product %s"),strip_tags($this->card->getName()));

		$first_product = $this->tpl_data["first_product"] = $this->card->getFirstProduct(array("visible" => null));

		$this->form->set_initial($this->card);
		$this->form->set_initial("tags",$this->card->getTags());
		//$this->form->set_initial("category_ids", $this->card->getCategories());
		if(!$this->card->hasVariants()){
			if($first_product){
				$this->form->set_initial(array(
					"catalog_id" => $first_product->getCatalogId(),
				));
				$this->form->fields["catalog_id"]->required = true;
			}else{
				$this->form->fields["catalog_id"]->required = false;
			}
		}
		$this->_save_return_uri();

		$this->tpl_data["add_to_category_form"] = $this->_get_form("AddToCategoryForm");
		$this->tpl_data["add_technical_specification_form"] = $this->_get_form("AddTechnicalSpecificationForm");
		$this->tpl_data["add_technical_specification_form"]->set_action($this->_link_to(array(
			"action" => "add_technical_specification",
			"id" => $this->card,
		)));
		$this->tpl_data["products"] = $this->card->getProducts(array("visible" => null));


		if ($this->request->post() && ($d=$this->form->validate($this->params))) {
			/*
			$category_ids = $d["category_ids"];
			unset($d["category_ids"]); */

			if(!$this->form->changed()){
				$this->_redirect_back();
				return;
			}

			$this->_save_slug_state($this->card);

			$tags = $d["tags"];
			unset($d["tags"]);

			$catalog_id = $d["catalog_id"];
			unset($d["catalog_id"]);

			if(!$this->card->hasVariants() && strlen($catalog_id)){
				if(!$first_product){
					$first_product = $this->card->createProduct([
						"catalog_id" => $catalog_id,
					]);
				}
				if($first_product->getCatalogId()!==$catalog_id){
					$first_product->s("catalog_id",$catalog_id);
				}
			}

			$this->card->s($d,array("reconstruct_missing_slugs" => true));
			$this->card->setTags($tags);

			/*
			$this->card->getCategoriesLister()->clear();
			foreach($category_ids as $cat) {
				$this->card->addToCategory($cat);
			}*/

			$this->flash->success(_("Changes have been saved"));
			$this->_redirect_back_or_edit_slug();
		}

		$this->_prepare_categories();
	}

	function destroy(){
		$this->_destroy();
	}

	function enable_variants(){
		if(!$this->request->post() || $this->card->hasVariants()){
			return $this->_execute_action("error404");
		}

		$this->card->s("has_variants",true);
		
		$this->flash->success(_("Variants mode has been activated"));
		$this->_redirect_to($this->_link_to(array(
			"action" => "edit",
			"id" => $this->card,
		))."#variants");
	}

	function disable_variants(){
		if(!$this->request->post() || !$this->card->hasVariants()){
			return $this->_execute_action("error404");
		}

		if(!$this->card->canBeSwitchedToNonVariantMode()){
			return $this->_execute_action("error404");
		}

		$this->card->s("has_variants",false);

		$this->flash->success(_("Variants mode has been deactivated"));
		$this->_redirect_to($this->_link_to(array(
			"action" => "edit",
			"id" => $this->card,
		))."#variants");
	}

	function add_to_category(){
		if(!$this->request->post()){ return $this->_execute_action("error404"); }

		if($d = $this->form->validate($this->params)){
			$this->card->addToCategory($d["category"]);
		}

		$this->_prepare_categories();
	}

	function add_technical_specification(){
		if(!$this->request->post()){ return $this->_execute_action("error404"); }

		if($d = $this->form->validate($this->params)){

			if(TechnicalSpecification::FindFirst("card_id",$this->card,"technical_specification_key_id",$d["technical_specification_key_id"])){
				$this->form->set_error("technical_specification_key_id",_("The product already has this specification"));
				return;
			}

			$d["card_id"] = $this->card;
			TechnicalSpecification::CreateNewRecord($d);

			if(!$this->request->xhr()){
				$this->_redirect_to(array(
					"action" => "edit",
					"id" => $this->card,
				));
				return;
			}

			$this->form = $this->_get_form("AddTechnicalSpecificationForm"); // fresh form
		}
	}

	function set_category_rank() {
		if(!$this->request->post()){ return $this->_execute_action("error404"); }
		$this->card->getCategoriesLister()->setRecordRank($this->category, $this->params["rank"]);
		$this->render_template = false;
	}

	function remove_from_category(){
		if(!$this->request->post()){ return $this->_execute_action("error404"); }

		$this->card->removeFromCategory($this->category);
	}

	function append_external_source() {
		$this->page_title = _("Připojení odkazu k produktu");
		$this->_save_return_uri();
		if($this->request->post() && ($d=$this->form->validate($this->params))) {

			$external_source = $this->_find("external_source","external_source_id");
			$this->card->appendExternalSource($external_source);
			$this->_redirect_back();
		}
	}

	function remove_external_source() {
		if(!$this->request->post()){ return $this->_execute_action("error404"); }

		$this->_save_return_uri();

		$external_source = $this->_find("external_source","source_id");
		$this->card->removeExternalSource($external_source);
		if (!$this->request->xhr()) {
			$this->flash->success(_("Odkaz odpojen"));
			return $this->_redirect_back();
		}
	}

	function _before_filter() {
		if (in_array($this->action, array("edit","destroy","enable_variants","disable_variants","add_to_category","add_technical_specification","remove_from_category","append_external_source","remove_external_source", "set_category_rank"))) {
			$card = $this->_find("card");
			if($card && $card->isDeleted()){
				return $this->_execute_action("error404");
			}
		}

		if (in_array($this->action, array("remove_from_category", "set_category_rank"))) {
			$this->_find("category","category_id");
		}
	}

	function _prepare_categories(){
		// nebudeme zobrazovat filtracni kategorie, na to je jina akce: card_filters/edit
		$categories = array();
		$filter_categories_count = 0;
		$filters = array();
		foreach($this->card->getCategories() as $c){
			if(($p = $c->getParentCategory()) && $p->isFilter()){
				$p_id = $p->getId();
				if(!isset($filters[$p_id])){ $filters[$p_id] = array("filter" => $p, "items" => array()); }
				$filters[$p_id]["items"][] = $c;
				$filter_categories_count++;
				continue;
			}
			$categories[] = $c;
		}
		$this->tpl_data["categories"] = $categories;
		$this->tpl_data["filter_categories_count"] = $filter_categories_count;
		$this->tpl_data["filters"] = $filters;
	}
}
