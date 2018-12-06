{a action="detail" id=$store _class="card"}
{!$store->getImageUrl()|pupiq_img:"!400x300":"class='card-img-top'"}
<div class="card-body">
	<h4 class="card-title">{$store->getName()}</h4>
	<div class="card-text">{$store->getTeaser()}</div>
</div>
{/a}