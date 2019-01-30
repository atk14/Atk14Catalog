<li>
	<h3>{$product->getName()}</h3>

	{!$product->getShortinfo()|markdown}

	{render partial="shared/photo_gallery" photo_gallery_title="" object=$product}
</li>
