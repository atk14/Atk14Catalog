{if $products}

	{foreach $products as $product}
		{if !$product->isVisible()}<span class="text-muted" title="{t}invisible{/t}">{/if}
		{$product->getCatalogId()}
		{if !$product->isVisible()}({!"eye-slash"|icon}) </span>{/if}
		{if !$product@last}<br>{/if}
	{/foreach}

{/if}
