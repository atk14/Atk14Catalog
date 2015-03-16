{assign var=collection_cards value=$collection->getCards()}

<ul class="list-group list-sortable" data-sortable-url="{link_to action="collection_cards/set_rank" collection_id=$collection}">
	{foreach $collection_cards as $ccard}
			<li class="list-group-item" data-id="{$ccard->getId()}">
				{render partial="shared/list_thumbnail" image=$ccard->getImage()}
				{$ccard->getName()}
				<ul class="list-inline pull-right">
					{capture assign="confirm"}{t 1=$ccard->getName()|h escape=no}You are about to remove the product %1 from the collection.
Are you sure about that?{/t}{/capture}
					<li>{a_remote action="collection_cards/remove" collection_id=$collection id=$ccard _method=post _confirm=$confirm _class="btn btn-danger btn-xs"}<i class="glyphicon glyphicon-remove"></i>{/a_remote}</li>
				</ul>
			</li>
	{/foreach}
</ul>
{a action="collection_cards/create_new" collection_id=$collection _class="btn btn-default"}<i class="glyphicon glyphicon-plus-sign"></i> {t}Add a product into the collection{/t}{/a}
