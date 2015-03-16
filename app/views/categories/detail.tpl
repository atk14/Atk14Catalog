<h1>{$page_title}</h1>

<p class="lead">{$category->getTeaser()}</p>

{!$category->getImageUrl()|pupiq_img:"300x300"}
{!$category->getDescription()|markdown}
