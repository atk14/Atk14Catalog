<h3 id="variants">
	{if $card->hasVariants()}
		{button_create_new action="products/create_new" card_id=$card return_to_anchor=variants}{t}Add a new variant{/t}{/button_create_new}
	{/if}
	{t 1=$products|@count}Product variants (%1){/t}
</h3>
{if !$card->hasVariants()}
	{t}Variants are not considered for this product{/t} &rarr; {a action=enable_variants id=$card _method=post _confirm="{t}Are you sure?{/t}"}{t}switch to the variant mode{/t}{/a}
{else}
	{assign products $card->getProducts()}
	{render partial="products" products=$products}
	{if sizeof($products)==1}
		{t}This product can be swithed to the non-variant mode{/t} &rarr; {a action=disable_variants id=$card _method=post _confirm="{t}Are you sure?{/t}"}{t}switch to the non-variant mode{/t}{/a}
	{/if}
{/if}
