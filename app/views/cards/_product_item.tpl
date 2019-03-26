<li>
	<h4>
		{if $product->getLabel()}{$product->getLabel()}{else}{$product->getName()}{/if}<br>
		<small>{t catalog_id=$product->getCatalogId()}catalog number: %1{/t}</small>
	</h4>


	{!$product->getDescription()|markdown}

	{render partial="shared/photo_gallery" photo_gallery_title="" object=$product}
</li>
