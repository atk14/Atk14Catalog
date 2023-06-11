<?php
/**
 * Renders markup for a Font Awesome icon
 *
 *	{!"edit"|icon}
 *	{!"remove"|icon}
 *
 *	{!"twitter"|icon:"brands"}
 *	{!"envelope"|icon:"solid"}
 *	{!"envelope"|icon:"regular"}
 *	{!"envelope"|icon:"light"}
 */
function smarty_modifier_icon($glyph,$style = ""){
	$tr_table = array(
		"remove" => "times",
		"eye-open" => "eye",
	);
	$glyph = isset($tr_table[$glyph]) ? $tr_table[$glyph] : $glyph;

	$style_auto_detection_tr = array(
		"brands" => array(
			// Brands taken from https://fontawesome.com/icons?d=gallery&s=brands
			"500px",
			"accessible-icon", "accusoft", "acquisitions-incorporated", "adn", "adversal", "affiliatetheme", "algolia", "alipay", "amazon", "amazon-pay", "amilia", "android", "angellist", "angrycreative", "angular", "app-store", "app-store-ios", "apper", "apple", "apple-pay", "asymmetrik", "audible", "autoprefixer", "avianex", "aviato", "aws",
			"bandcamp", "behance", "behance-square", "bimobject", "bitbucket", "bitcoin", "bity", "black-tie", "blackberry", "blogger", "blogger-b", "bluetooth", "bluetooth-b", "btc", "buromobelexperte", "buysellads",
			"cc-amazon-pay", "cc-amex", "cc-apple-pay", "cc-diners-club", "cc-discover", "cc-jcb", "cc-mastercard", "cc-paypal", "cc-stripe", "cc-visa", "centercode", "chrome", "cloudscale", "cloudsmith", "cloudversify", "codepen", "codiepie", "connectdevelop", "contao", "cpanel", "creative-commons", "creative-commons-by", "creative-commons-nc", "creative-commons-nc-eu", "creative-commons-nc-jp", "creative-commons-nd", "creative-commons-pd", "creative-commons-pd-alt", "creative-commons-remix", "creative-commons-sa", "creative-commons-sampling", "creative-commons-sampling-plus", "creative-commons-share", "creative-commons-zero", "critical-role", "css3", "css3-alt", "cuttlefish",
			"d-and-d", "d-and-d-beyond", "dashcube", "delicious", "deploydog", "deskpro", "dev", "deviantart", "digg", "digital-ocean", "discord", "discourse", "dochub", "docker", "draft2digital", "dribbble", "dribbble-square", "dropbox", "drupal", "dyalog",
			"earlybirds", "ebay", "edge", "elementor", "ello", "ember", "empire", "envira", "erlang", "ethereum", "etsy", "expeditedssl",
			"facebook", "facebook-f", "facebook-messenger", "facebook-square", "fantasy-flight-games", "firefox", "first-order", "first-order-alt", "firstdraft", "flickr", "flipboard", "fly", "font-awesome", "font-awesome-alt", "font-awesome-flag", "fonticons", "fonticons-fi", "fort-awesome", "fort-awesome-alt", "forumbee", "foursquare", "free-code-camp", "freebsd", "fulcrum",
			"galactic-republic", "galactic-senate", "get-pocket", "gg", "gg-circle", "git", "git-square", "github", "github-alt", "github-square", "gitkraken", "gitlab", "gitter", "glide", "glide-g", "gofore", "goodreads", "goodreads-g", "google", "google-drive", "google-play", "google-plus", "google-plus-g", "google-plus-square", "google-wallet", "gratipay", "grav", "gripfire", "grunt", "gulp",
			"hacker-news", "hacker-news-square", "hackerrank", "hips", "hire-a-helper", "hooli", "hornbill", "hotjar", "houzz", "html5", "hubspot",
			"imdb", "instagram", "internet-explorer", "ioxhost", "itunes", "itunes-note",
			"java", "jedi-order", "jenkins", "joget", "joomla", "js", "js-square", "jsfiddle",
			"kaggle", "keybase", "keycdn", "kickstarter", "kickstarter-k", "korvue",
			"laravel", "lastfm", "lastfm-square", "leanpub", "less", "line", "linkedin", "linkedin-in", "linode", "linux", "lyft",
			"magento", "mailchimp", "mandalorian", "markdown", "mastodon", "maxcdn", "medapps", "medium", "medium-m", "medrt", "meetup", "megaport", "microsoft", "mix", "mixcloud", "mizuni", "modx", "monero",
			"napster", "neos", "nimblr", "nintendo-switch", "node", "node-js", "npm", "ns8", "nutritionix",
			"odnoklassniki", "odnoklassniki-square", "old-republic", "opencart", "openid", "opera", "optin-monster", "osi",
			"page4", "pagelines", "palfed", "patreon", "paypal", "penny-arcade", "periscope", "phabricator", "phoenix-framework", "phoenix-squadron", "php", "pied-piper", "pied-piper-alt", "pied-piper-hat", "pied-piper-pp", "pinterest", "pinterest-p", "pinterest-square", "playstation", "product-hunt", "pushed", "python",
			"qq", "quinscape", "quora",
			"r-project", "ravelry", "react", "reacteurope", "readme", "rebel", "red-river", "reddit", "reddit-alien", "reddit-square", "renren", "replyd", "researchgate", "resolving", "rev", "rocketchat", "rockrms",
			"safari", "sass", "schlix", "scribd", "searchengin", "sellcast", "sellsy", "servicestack", "shirtsinbulk", "shopware", "simplybuilt", "sistrix", "sith", "skyatlas", "skype", "slack", "slack-hash", "slideshare", "snapchat", "snapchat-ghost", "snapchat-square", "soundcloud", "speakap", "spotify", "squarespace", "stack-exchange", "stack-overflow", "staylinked", "steam", "steam-square", "steam-symbol", "sticker-mule", "strava", "stripe", "stripe-s", "studiovinari", "stumbleupon", "stumbleupon-circle", "superpowers", "supple",
			"teamspeak", "telegram", "telegram-plane", "tencent-weibo", "the-red-yeti", "themeco", "themeisle", "think-peaks", "trade-federation", "trello", "tripadvisor", "tumblr", "tumblr-square", "twitch", "twitter", "twitter-square", "typo3",
			"uber", "uikit", "uniregistry", "untappd", "usb", "ussunnah",
			"vaadin", "viacoin", "viadeo", "viadeo-square", "viber", "vimeo", "vimeo-square", "vimeo-v", "vine", "vk", "vnv", "vuejs",
			"weebly", "weibo", "weixin", "whatsapp", "whatsapp-square", "whmcs", "wikipedia-w", "windows", "wix", "wizards-of-the-coast", "wolf-pack-battalion", "wordpress", "wordpress-simple", "wpbeginner", "wpexplorer", "wpforms", "wpressr",
			"xbox", "xing", "xing-square",
			"y-combinator", "yahoo", "yandex", "yandex-international", "yelp", "yoast", "youtube", "youtube-square",
			"zhihu",
		)
	);

	if(!$style){
		foreach($style_auto_detection_tr as $s => $glyphs){
			if(in_array($glyph,$glyphs)){
				$style = $s;
				break;
			}
		}
	}

	if(!$style){
		$style = "solid"; // the default style
	}

	$s = $style[0]; // "style" -> "s"
	return sprintf('<span class="fa%s fa-%s"></span>',$s,$glyph);
}
