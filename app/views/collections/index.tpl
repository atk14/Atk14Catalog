<h1>{$page_title}</h1>

<ul>
{foreach $collections as $collection}
	<li>
		{a action="detail" id=$collection}
		{!$collection->getImageUrl()|pupiq_img:"!100x100"}
		<h3>{$collection->getName()}</h3>
		{/a}
		<p>{$collection->getTeaser()}</p>
	</li>
{/foreach}
</ul>
