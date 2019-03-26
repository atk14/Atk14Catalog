<?php
class TcTranslation extends TcBase{
	function test(){
		global $ATK14_GLOBAL;
		$supported_langs = $ATK14_GLOBAL->getSupportedLangs();

		// --
		$brand = Brand::CreateNewRecord(array(
		));
		$this->assertEquals(true,$brand->getVisible());
		$this->assertEquals(null,$brand->getDescription());
		$this->assertEquals(null,$brand->getDescription("cs"));
		$this->assertEquals(null,$brand->getDescription("en"));

		// --
		$brand_atk14 = Brand::CreateNewRecord(array(
			"name" => "ATK14 Framework",
			"description_cs" => "PHP framework pro nebojácné chlapce a děvčata",
			"description_en" => "PHP framework for fearless guys"
		));
		$ar = $brand_atk14->toArray();

		$this->assertEquals("ATK14 Framework",$ar["name"]);
		$this->assertEquals("PHP framework pro nebojácné chlapce a děvčata",$ar["description_cs"]);
		$this->assertEquals("PHP framework for fearless guys",$ar["description_en"]);

		$brand_atk14->s("description_en","PHP framework for fearless guys for now and ever after");
		$ar = $brand_atk14->toArray();
		$this->assertEquals("ATK14 Framework",$ar["name"]);
		$this->assertEquals("PHP framework for fearless guys for now and ever after",$ar["description_en"]);

		$this->assertEquals("PHP framework pro nebojácné chlapce a děvčata",$brand_atk14->getDescription("cs"));
		$this->assertEquals("PHP framework for fearless guys for now and ever after",$brand_atk14->getDescription("en"));
		$this->assertEquals(null,$brand_atk14->getDescription("hu"));

		// bez parametru je vracena hodnota v akt. jazyce
		$ATK14_GLOBAL->setValue("lang","cs");
		$this->assertEquals("PHP framework pro nebojácné chlapce a děvčata",$brand_atk14->getDescription());

		$ATK14_GLOBAL->setValue("lang","en");
		$this->assertEquals("PHP framework for fearless guys for now and ever after",$brand_atk14->getDescription());
		$ATK14_GLOBAL->setValue("lang","cs");

		// --
		$brand = Brand::CreateNewRecord(array(
			"name" => "Javor & Syn",
			"description_cs" => "Javorový nábytek",
		));
		$ar = $brand->toArray();
		$keys = array_keys($ar);

		if(in_array("en",$supported_langs)){
			$this->assertTrue(in_array("description_en",$keys));
			$this->assertEquals(null,$ar["description_en"]);
		}
		$this->assertEquals("Javorový nábytek",$ar["description_cs"]);

		$ar["description_en"] = "Maple furniture";
		$brand->s($ar);
		$ar = $brand->toArray();
		$this->assertEquals("Maple furniture",$ar["description_en"]);
		$this->assertEquals("Javorový nábytek",$ar["description_cs"]);

		// -- mazani
		$count = $this->dbmole->selectInt("SELECT COUNT(*) FROM translations");
		$brand->destroy();
		$count2 = $this->dbmole->selectInt("SELECT COUNT(*) FROM translations");
		$this->assertTrue($count>0);
		$this->assertTrue($count2>0);
		$this->assertTrue($count>$count2);

		// -- po smazani zustanou data pro ostatni objekty nedknuta
		$brand = Brand::GetInstanceById($brand_atk14->getId());
		$this->assertEquals("PHP framework pro nebojácné chlapce a děvčata",$brand->getDescription());

		// fallback language, see ../../config/locale.yml
		$brand = Brand::CreateNewRecord(array(
			"name" => "Snake Oil",
			"description_cs" => "",
			"description_en" => "Medicine that doesn't work"
		));
		$this->assertEquals("Medicine that doesn't work",$brand->getDescription("en"));
		$this->assertEquals("Medicine that doesn't work",$brand->getDescription("cs")); // english is the fallback language for czech
		$this->assertEquals("Medicine that doesn't work",$brand->g("description_en"));
		$this->assertEquals("",$brand->g("description_cs"));

		$brand = Brand::CreateNewRecord(array(
			"name" => "Snake Oil",
			"description_cs" => "Medicína, která nefunguje!",
			"description_en" => ""
		));
		$this->assertEquals("",$brand->getDescription("en")); // no fallback language for english
		$this->assertEquals("Medicína, která nefunguje!",$brand->getDescription("cs"));
		$this->assertEquals("",$brand->g("description_en"));
		$this->assertEquals("Medicína, která nefunguje!",$brand->g("description_cs"));
	}


	function test_build_conditions() {
		list($conditions, $bind_ar) = Translation::BuildConditionsForTranslatableFields("articles",array("teaser","name"));
		$this->assertEquals("id IN (SELECT record_id FROM translations WHERE upper(translations.body) LIKE upper(:search) AND translations.key IN :search_fields AND translations.lang=:lang AND translations.table_name=:table_name_articles)", $conditions[0]);
		$this->assertEquals(array(
			":search_fields" => array("teaser","name"),
			":lang"=> "en",
			":table_name_articles" => "articles",
		), $bind_ar);


		$conditions = array("deleted='f'");
		$bind_ar = array();
		list($conditions, $bind_ar) = Translation::BuildConditionsForTranslatableFields("articles",array("teaser","name"),$conditions,$bind_ar);
		$this->assertEquals("deleted='f'", $conditions[0]);
		$this->assertEquals("id IN (SELECT record_id FROM translations WHERE upper(translations.body) LIKE upper(:search) AND translations.key IN :search_fields AND translations.lang=:lang AND translations.table_name=:table_name_articles)", $conditions[1]);
		$this->assertEquals(array(
			":lang"=> "en",
			":search_fields" => array("teaser","name"),
			":table_name_articles"=> "articles",
		), $bind_ar);
	}
}
