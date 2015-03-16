<?php
class FulfillingCategoriesMigration extends ApplicationMigration{
	function up(){
		$yml = '
---
- en: Toys, Children & Baby | Extraordinary toys for your extraordinary child
  cs: Hračky, děti a miminka | Mimořádné hračky pro vaše mimořádné dítě
  sub:
  - en: Electronic Toys
    cs: Elektronické hračky
  - en: Baby & Toddler Toys
    cs: Hračky pro batolata
  - en: Model Vehicles 
    cs: Modely vozidel
- en: Books | Reading books is just fine. Here we offer some great books.
  cs: Knihy | Čtení knih je prostě bezva. Tady nabízíme několik skvělých knih.
  sub:
  - en: Food & Drink 
    cs: Jídlo a nápoje
  - en: History
    cs: Historie
  - en: Computing & Internet
    cs: Počítače a Internet
  - en: Humour | Humour is the spice of life
    cs: Humor | Humor je koření života
  - en: Children
    cs: Děti
';


		$items = miniYAML::Load($yml);
		$this->_importCategoryItems($items);

		// Creating an alias: "Toys, Children & Baby / Books" -> "Books / Children"
		$lang = "en";
		$parent = Category::GetInstanceByPath("toys-children-baby",$lang);
		$category_to_pointing_to = Category::GetInstanceByPath("books/children",$lang);
		$alias = $this->_createCategory(array(
			"name_en" => "Books",
			"name_cs" => "Knihy",
			"parent_category_id" => $parent,
			"pointing_to_category_id" => $category_to_pointing_to,
		));
	}

	function _importCategoryItems($items,$parent_category = null){
		foreach($items as $item){
			$item += array("sub" => array());
			$sub = $item["sub"];
			unset($item["sub"]);
			$values = array();
			foreach($item as $key => $str){
				if(strlen($key)==2){
					$lang = $key;
					$str = preg_split('/\s*\|\s*/',$str);
					$values["name_$lang"] = $str[0];
					$values["teaser_$lang"] = isset($str[1]) ? $str[1] : null;
					continue;
				}
				$values[$key] = $str;
			}
			$values["parent_category_id"] = $parent_category;
			$c = $this->_createCategory($values);
			$this->_importCategoryItems($sub,$c);
		}
	}

	function _createCategory($values){
		static $counter = 0, $lorem_ipsum;
		if(!$lorem_ipsum){
			$lorem_ipsum = $this->_lipsumParagraphs();
		}
		$counter++;
		$values += array(
			"image_url" => "http://www.fillmurray.com/".(400+$counter)."/".(400+$counter),
			"description_cs" => $lorem_ipsum,
			"description_en" => $lorem_ipsum,
		);
		return Category::CreateNewRecord($values);
	}
}
