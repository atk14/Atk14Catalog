{*
 * Common template for Related products, Consumables and Accessories
 *
 *	{render partial="cards_list" type="related_cards" cards=$card->getRelatedCards() button_title="{t}Add related product{/t}" empty_list_message="{t}There is no related product{/t}"}
 *
 *}

<h2>{button_create_new action="$type/create_new" card_id=$card}{$button_title}{/button_create_new} {$title}</h2>

{if $cards}

	<ul class="list-group list-sortable" data-sortable-url="{link_to action="$type/set_rank" card_id=$card}">
		{foreach $cards as $c}
			<li class="list-group-item clearfix" data-id="{$c->getId()}">
				{$c->getName()}
				<ul class="list-inline pull-right">
					{capture assign="confirm"}{t 1=$c->getName()|h escape=no}You are going to remove a related product %1. Are you sure?{/t}{/capture}
					<li>{a_remote action="$type/remove" card_id=$card id=$c _method=post _confirm=$confirm _class="btn btn-danger btn-xs"}<i class="glyphicon glyphicon-remove"></i>{/a_remote}</li>
				</ul>
			</li>
		{/foreach}
	</ul>

{else}

	<p>{$empty_list_message}</p>

{/if}
