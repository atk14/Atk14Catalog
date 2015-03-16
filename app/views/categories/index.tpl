<h1>{$page_title}</h1>

<ul>
	{foreach $categories as $category}
	<li>
		{!$category->getImageUrl()|pupiq_img:"!100x100"}
		<h4>{a action="detail" path=$category->getSlug()}{$category->getName()}{/a}</h4>
		{$category->getTeaser()}
	</li>
	{/foreach}
</ul>
