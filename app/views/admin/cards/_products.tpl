{if $products}

	<ul class="list-group list-sortable" data-sortable-url="{link_to action="products/set_rank"}">
		{foreach $products as $product}
			<li class="list-group-item" data-id="{$product->getId()}">
				{render partial="shared/list_thumbnail" image=$product->getImage()}
				<strong>{$product->getCatalogId()}</strong> {$product->getName()}
				<ul class="list-inline pull-right">
					<li>{a action="products/edit" id=$product}<i class="glyphicon glyphicon-edit"></i> {t}Edit{/t}{/a}</li>
					{capture assign="confirm"}{t 1=$product->getName()|h escape=no}Chystáte se smazat produkt %1. Jste si jistý/á?{/t}{/capture}
					<li>{a_remote action="products/destroy" id=$product _method=post _confirm=$confirm _class="btn btn-danger btn-xs"}<i class="glyphicon glyphicon-remove"></i>{/a_remote}</li>
				</ul>
			</li>
		{/foreach}
	</ul>

{else}

	<p>{t}Produkt zatím nemá žádnou variantu.{/t}</p>

{/if}
