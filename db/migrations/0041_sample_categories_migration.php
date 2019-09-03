<?php
class SampleCategoriesMigration extends ApplicationMigration{

	function up(){
		if(TEST){ return; }

		$yml = '
---
- en: Catalog
  cs: Katalog
  code: catalog
  sub:
  - en: Toys, Children & Babies | Extraordinary toys for your extraordinary children
    cs: Hračky, děti a miminka | Mimořádné hračky pro vaše mimořádné děti
    image_url: http://i.pupiq.net/i/65/65/a40/1a40/1489x1289/Tfmb1G_800x693_0aab5602365b4819.jpg
    sub:
    - en: Electronic Toys
      cs: Elektronické hračky
      image_url: http://i.pupiq.net/i/65/65/a42/1a42/1500x1500/iqurhB_800x800_d1e196917fd9607f.jpg
    - en: Baby & Toddler Toys
      cs: Hračky pro batolata
      image_url: http://i.pupiq.net/i/65/65/a43/1a43/800x800/WTD4Bl_800x800_04a846b1317b4c91.jpg
    - en: Model Vehicles 
      cs: Modely vozidel
      image_url: http://i.pupiq.net/i/65/65/a44/1a44/440x318/6hmU7k_440x318_1672a07b4848891f.jpg
  - en: Books | Reading books is just fine. Here we offer some great books.
    cs: Knihy | Čtení knih je prostě bezva. Tady nabízíme několik skvělých knih.
    image_url: http://i.pupiq.net/i/65/65/a41/1a41/500x434/jVDLvb_500x434_cbc06e116526b779.jpg
    sub:
    - en: Food & Drink 
      cs: Jídlo a nápoje
      image_url: http://i.pupiq.net/i/65/65/a45/1a45/733x594/n1lO92_733x594_132f9ea94c3a3e77.jpg
    - en: History
      cs: Historie
      image_url: http://i.pupiq.net/i/65/65/a46/1a46/1024x678/BiP63Q_800x530_5e330d5f209d51aa.jpg
    - en: Computing & Internet
      cs: Počítače a Internet
      image_url: http://i.pupiq.net/i/65/65/a47/1a47/650x329/Snvusm_650x329_304bd8558f67555a.jpg
    - en: Humour | Humour is the spice of life
      cs: Humor | Humor je koření života
      image_url: http://i.pupiq.net/i/65/65/a48/1a48/613x440/zMO4Q9_613x440_af7b969b30a2c124.jpg
    - en: Children
      cs: Děti
      image_url: http://i.pupiq.net/i/65/65/a49/1a49/404x297/NLEGR4_404x297_d23944dda34d3197.jpg
';


		$items = miniYAML::Load($yml);
		$this->_importCategoryItems($items);

		// Creating an alias: "Toys, Children & Baby / Books" -> "Books / Children"
		$lang = "en";
		$parent = Category::GetInstanceByPath("catalog/toys-children-babies",$lang);
		$category_to_pointing_to = Category::GetInstanceByPath("catalog/books/children",$lang);
		myAssert(is_object($parent) && is_object($category_to_pointing_to));
		$alias = $this->_createCategory(array(
			"name_en" => "Books",
			"name_cs" => "Knihy",
			"parent_category_id" => $parent,
			"pointing_to_category_id" => $category_to_pointing_to,
      "image_url" => "http://i.pupiq.net/i/65/65/a49/1a49/404x297/NLEGR4_404x297_d23944dda34d3197.jpg"
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
			"description_cs" => $lorem_ipsum,
			"description_en" => $lorem_ipsum,
		);
		return Category::CreateNewRecord($values);
	}
}
