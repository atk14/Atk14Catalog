<h1>{$page_title}</h1>

<ul>
{foreach $brands as $brand}
	<li>
		{a action="detail" id=$brand}
		{!$brand->getLogoUrl()|pupiq_img:"!100x100"}
		<h3>{$brand->getName()}</h3>
		{/a}
		<p>{$brand->getTeaser()}</p>
	</li>
{/foreach}
</ul>
