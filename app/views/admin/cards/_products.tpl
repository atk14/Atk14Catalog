{if $products}

	<ul class="list-group list-sortable" data-sortable-url="{link_to action="products/set_rank"}">
		{foreach $products as $product}
			<li class="list-group-item" data-id="{$product->getId()}">
				{render partial="shared/list_thumbnail" image=$product->getImage()}
				<strong>{$product->getCatalogId()}</strong> {$product->getLabel()}

				{dropdown_menu}
					{a action="products/edit" id=$product}{icon glyph="edit"} {t}Edit{/t}{/a}
					{capture assign="confirm"}{t 1=$product->getName()|h escape=no}Chystáte se smazat produkt %1. Jste si jistý/á?{/t}{/capture}
					{a_destroy action="products/destroy" id=$product _confirm=$confirm}{icon glyph="remove"} {t}Delete{/t}{/a_destroy}
				{/dropdown_menu}
			</li>
		{/foreach}
	</ul>

{else}

	<p>{t}Produkt zatím nemá žádnou variantu.{/t}</p>

{/if}
