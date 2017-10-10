{*
 * Common template for Related products, Consumables and Accessories
 *
 *	{render partial="cards_list" type="related_cards" cards=$card->getRelatedCards() button_title="{t}Add related product{/t}" empty_list_message="{t}There is no related product{/t}"}
 *
 *}

<h3 id="{$type}">{button_create_new action="$type/create_new" card_id=$card return_to_anchor=$type}{$button_title}{/button_create_new} {$title}</h3>

{if $cards}

	<ul class="list-group list-sortable" data-sortable-url="{link_to action="$type/set_rank" card_id=$card}">
		{foreach $cards as $c}
			<li class="list-group-item clearfix" data-id="{$c->getId()}">
	
				{render partial="shared/list_thumbnail" image=$c->getImage()}
				
				{$c->getName()}

				{dropdown_menu}
					{a action="cards/edit" id=$c}{icon glyph=edit} {t}Edit this product{/t}{/a}
					{capture assign="confirm"}{t 1=$c->getName()|h escape=no}You are going to remove a related product %1. Are you sure?{/t}{/capture}
					{a_remote action="$type/remove" card_id=$card id=$c _method=post _confirm=$confirm}{icon glyph=remove} {t}Remove{/t}{/a_remote}
				{/dropdown_menu}
			</li>
		{/foreach}
	</ul>

{else}

	<p>{$empty_list_message}</p>

{/if}
