<?php
class SampleStoresMigration extends ApplicationMigration {

	function up(){
		$store = Store::CreateNewRecord(array(
			"image_url" => "http://i.pupiq.net/i/65/65/b65/4b65/736x855/stiz2J_736x855_b562e30b5556c4dc.jpg",
			"phone" => "+420.606123456",
			"email" => "drugstore@email.com",
			"address_cs" => "Vinohradská 222
120 00 Praha 2
Česká republika",
			"address_en" => "Vinohradska 222
120 00 Prague 2
Czech Republic",
			"description_cs" => " Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur et eleifend nibh, ac tincidunt turpis. Morbi mauris nisl, euismod sed posuere non, consequat non urna. Morbi mattis lorem rhoncus porttitor sagittis. Fusce semper at lacus at suscipit. Vestibulum nec tincidunt justo, a sodales velit. Maecenas vitae tincidunt augue. Ut sed nunc id arcu posuere sodales non quis enim. Proin venenatis suscipit enim non volutpat. Duis nec rhoncus tortor. Vestibulum vestibulum ex rutrum, malesuada elit ac, varius mauris. Aliquam porta dui at luctus elementum. Etiam convallis lorem at augue venenatis, a tempus nibh venenatis. Maecenas id venenatis velit.

Quisque laoreet, tortor eu mollis faucibus, leo eros consectetur dolor, eu feugiat ante justo id magna. Suspendisse ut venenatis libero, sed tincidunt lectus. Nulla ut nisi aliquet, varius purus at, tempor nisl. Duis dignissim dui quis arcu aliquet gravida. Mauris elementum ex vel tincidunt gravida. Etiam congue ipsum est. Nullam vel dolor ultricies velit iaculis efficitur sit amet ut velit. Donec mollis varius diam in tempus. Vivamus urna tellus, convallis vitae ullamcorper in, auctor et turpis. Etiam porta lorem lectus. Aliquam at odio nisl. Aenean nec massa tristique magna lobortis pulvinar vitae et orci. Fusce purus ex, auctor ut hendrerit id, suscipit nec elit. Fusce vitae mauris a dui placerat iaculis sit amet in sapien. Etiam sed magna ornare, luctus nisl et, tincidunt mi. Aliquam vitae nulla a nisi tempus elementum a faucibus arcu. ",
			"description_en" => " Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur et eleifend nibh, ac tincidunt turpis. Morbi mauris nisl, euismod sed posuere non, consequat non urna. Morbi mattis lorem rhoncus porttitor sagittis. Fusce semper at lacus at suscipit. Vestibulum nec tincidunt justo, a sodales velit. Maecenas vitae tincidunt augue. Ut sed nunc id arcu posuere sodales non quis enim. Proin venenatis suscipit enim non volutpat. Duis nec rhoncus tortor. Vestibulum vestibulum ex rutrum, malesuada elit ac, varius mauris. Aliquam porta dui at luctus elementum. Etiam convallis lorem at augue venenatis, a tempus nibh venenatis. Maecenas id venenatis velit.

Quisque laoreet, tortor eu mollis faucibus, leo eros consectetur dolor, eu feugiat ante justo id magna. Suspendisse ut venenatis libero, sed tincidunt lectus. Nulla ut nisi aliquet, varius purus at, tempor nisl. Duis dignissim dui quis arcu aliquet gravida. Mauris elementum ex vel tincidunt gravida. Etiam congue ipsum est. Nullam vel dolor ultricies velit iaculis efficitur sit amet ut velit. Donec mollis varius diam in tempus. Vivamus urna tellus, convallis vitae ullamcorper in, auctor et turpis. Etiam porta lorem lectus. Aliquam at odio nisl. Aenean nec massa tristique magna lobortis pulvinar vitae et orci. Fusce purus ex, auctor ut hendrerit id, suscipit nec elit. Fusce vitae mauris a dui placerat iaculis sit amet in sapien. Etiam sed magna ornare, luctus nisl et, tincidunt mi. Aliquam vitae nulla a nisi tempus elementum a faucibus arcu. ",
			"name_cs" => "Elegantní lékarna",
			"name_en" => "Elegant drugstore",
			"opening_hours_cs" => "Po - Pá: 10:00 - 19:30
Sobota: 10:00 - 18:00",
			"opening_hours_en" => "Mon - Fri: 10:00 - 19:30
Saturday: 10:00 - 18:00",
			"teaser_cs" => "Praha",
			"teaser_en" => "Prague",

			"location_lat" => 50.0770708,
			"location_lng" => 14.4862577,
		));

		Image::AddImage($store,"http://i.pupiq.net/i/65/65/b66/4b66/550x412/2JcUiK_550x412_26e34525384f3cd3.jpg");
		Image::AddImage($store,"http://i.pupiq.net/i/65/65/b67/4b67/736x981/RQgUIz_736x980_274d7c80f196c00a.jpg");
		Image::AddImage($store,"http://i.pupiq.net/i/65/65/b66/4b66/550x412/2JcUiK_550x412_26e34525384f3cd3.jpg");
		Image::AddImage($store,"http://i.pupiq.net/i/65/65/b68/4b68/1280x800/LwVGg6_1024x640_6ebeba790731b400.jpg");
		Image::AddImage($store,"http://i.pupiq.net/i/65/65/b69/4b69/900x600/6cW0vh_900x599_f2f4c191d6e954b8.jpg");
	}
}
