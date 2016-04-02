<?php
class TcImage extends TcBase{
	function test(){
		// karta produktu
		$card = Card::CreateNewRecord(array());
		$this->assertEquals(array(),Image::GetImages($card));

		// Image implementuje Rankable, defaultne se tridi podle "rank, id"
		$image = Image::AddImage($card,"first.jpg");
		$this->assertEquals("Image",get_class($image));
		$this->assertEquals("first.jpg","$image");
		$this->assertEquals(false,$image->hasKey("display_on_card"));
		$ar = Image::GetImages($card);
		$this->assertEquals(1,sizeof($ar));
		$this->assertEquals("Image",get_class($ar[0]));
		$first_image = Image::FindFirst("table_name",$card->getTableName(),"record_id",$card);
		$this->assertEquals($image->getId(),$first_image->getId());

		$image2 = Image::AddImage($card,"second.jpg");
		$ar = Image::GetImages($card);
		$this->assertEquals(2,sizeof($ar));
		$this->assertEquals($image->getId(),$ar[0]->getId());
		$this->assertEquals($image2->getId(),$ar[1]->getId());
		$first_image = Image::FindFirst("table_name",$card->getTableName(),"record_id",$card);
		$this->assertEquals($image->getId(),$first_image->getId());

		$image2->setRank(0); // prvni misto
		$ar = Image::GetImages($card);
		$this->assertEquals(2,sizeof($ar));
		$this->assertEquals($image2->getId(),$ar[0]->getId());
		$this->assertEquals($image->getId(),$ar[1]->getId());
		$first_image = Image::FindFirst("table_name",$card->getTableName(),"record_id",$card);
		$this->assertEquals($image2->getId(),$first_image->getId());

		$image->setRank(1); // porad 2. misto
		$ar = Image::GetImages($card);
		$this->assertEquals(2,sizeof($ar));
		$this->assertEquals($image2->getId(),$ar[0]->getId());
		$this->assertEquals($image->getId(),$ar[1]->getId());
		$first_image = Image::FindFirst("table_name",$card->getTableName(),"record_id",$card);
		$this->assertEquals($image2->getId(),$first_image->getId());
		$first_image = Image::FindFirst("table_name",$card->getTableName(),"record_id",$card,array("order_by" => "rank DESC"));
		$this->assertEquals($image->getId(),$first_image->getId());

		// produkt samotny
		$product = Product::CreateNewRecord(array("card_id" => $card, "catalog_id" => "123/345"));
		$this->assertEquals(array(),Image::GetImages($product));

		$p_image = Image::AddImage($product,"product1.jpg");
		$p_image->s("display_on_card",false);
		$this->assertEquals("ProductImage",get_class($p_image));
		$this->assertEquals(true,$p_image->hasKey("display_on_card"));
		$ar = Image::GetImages($product);
		$this->assertEquals(1,sizeof($ar));
		$this->assertEquals("ProductImage",get_class($ar[0]));

		$this->assertEquals(false,!!ProductImage::GetInstanceById($image->getId()));
		$this->assertEquals(true,!!Image::GetInstanceById($p_image->getId())); // toto je zasadni -> zaznam je v tabulce images i product_images, nebot product_images je dedic tabulky images

		$p_image2 = Image::AddImage($product,"product2.jpg");

		//
		$image->setRank(0);
		$image2->setRank(1);
		//
		$card->s("has_variants",false); // the card has no variants -> only card images must be returned
		$images = $card->getImages();
		$this->assertEquals(2,sizeof($images));
		$this->assertEquals("first.jpg",$images[0]->getUrl());
		$this->assertEquals("second.jpg",$images[1]->getUrl());
		//
		$card->s("has_variants",true); // the card has variants -> card images + images from all products must by returned
		$images = $card->getImages();
		$this->assertEquals(3,sizeof($images));
		$this->assertEquals("first.jpg",$images[0]->getUrl());
		$this->assertEquals("second.jpg",$images[1]->getUrl());
		$this->assertEquals("product2.jpg",$images[2]->getUrl());
	}
}
