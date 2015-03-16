<h1>{$page_title}</h1>

<p class="lead">{$collection->getTeaser()}</p>

{!$collection->getImageUrl()|pupiq_img:"300x300"}
{!$collection->getDescription()|markdown}

{render partial="shared/photo_gallery" object=$collection}

{capture assign=title}{t collection=$collection->getName()}Products in the collection %1{/t}{/capture}
{render partial="shared/card_list" cards=$collection->getCards() title=$title}
