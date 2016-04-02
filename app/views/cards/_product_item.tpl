{assign var=image value=$product->getImage()}

<li>
	<h3>{$product->getName()}</h3>

	{if $image}
		<a href="{$image|img_url:1024}">{!$product->getImage()|pupiq_img:"200x200"}</a>
	{/if}

	{!$product->getShortinfo()|markdown}
</li>
