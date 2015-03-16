<h1>{$page_title}</h1>

<p class="lead">{$brand->getTeaser()}</p>

{!$brand->getLogoUrl()|pupiq_img:"300x300"}
{!$brand->getDescription()|markdown}

{render partial="shared/photo_gallery" object=$brand}

{render partial="shared/card_list" cards=$brand->getCards()}
