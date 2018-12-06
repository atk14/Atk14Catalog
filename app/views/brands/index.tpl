<header>
	<h1>{$page_title}</h1>
</header>

<div class="card-deck card-deck--sized">
{foreach $brands as $brand}
	
	{a action="detail" id=$brand _class="card"}
		{!$brand->getLogoUrl()|pupiq_img:"!400x400":"class='card-img-top'"}
		<div class="card-body">
			<h4 class="card-title">{$brand->getName()}</h4>
			<div class="card-text">{$brand->getTeaser()}</div>
		</div>
	{/a}
	
{/foreach}
</div>