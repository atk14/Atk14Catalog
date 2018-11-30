{*
 * {render partial="shared/card_list" cards=$collection->getCards() title="{t}Products in the collection{/t}"}
 *}

{if $cards}
	<section class="card-list">
		<h4>{$title|default:"{t}List of Products{/t}"}</h4>
		<div class="card-deck card-deck--sized">
		{foreach $cards as $card}
			{render partial="shared/card_item" card=$card}
		{/foreach}
		</div>
	</section>
{/if}