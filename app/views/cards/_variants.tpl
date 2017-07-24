{assign products $card->getProducts()}

{if $products}
	<ul>
		{render partial="product_item" from=$card->getProducts() item=product}
	</ul>
{/if}
