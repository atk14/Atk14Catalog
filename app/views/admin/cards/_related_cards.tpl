{assign var=related_cards value=$card->getRelatedCards()}
{if $related_cards}
	<ul class="list-group list-sortable" data-sortable-url="{link_to action="related_cards/set_rank"}">
		{foreach $related_cards as $rcard}
			<li class="list-group-item" data-id="{$rcard->getId()}">
				{$rcard->getName()}
				<ul class="list-inline pull-right">
					{capture assign="confirm"}{t 1=$rcard->getName()|h escape=no}Chystáte se odstranit související produkt %1. Jste si jistý/á?{/t}{/capture}
					<li>{a_remote action="related_cards/remove" card_id=$card id=$rcard _method=post _confirm=$confirm _class="btn btn-danger btn-xs"}<i class="glyphicon glyphicon-remove"></i>{/a_remote}</li>
				</ul>
			</li>
		{/foreach}
	</ul>
{else}
	<p>{t}Nejsou určené související produkty{/t}</p>
{/if}

{a action="related_cards/create_new" card_id=$card _class="btn btn-default"}<i class="glyphicon glyphicon-plus-sign"></i> {t}Přidat související produkt{/t}{/a}
