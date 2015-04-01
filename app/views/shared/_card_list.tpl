{*
 * {render partial="shared/card_list" cards=$collection->getCards() title="{t}Products in the collection{/t}"}
 *}

{if $cards}
	<section class="card-list">
		<h4>{$title|default:"{t}List of Products{/t}"}</h4>
		<ul>
		{foreach $cards as $card}
			<li>
				{render partial="shared/card_item" card=$card}
			</li>
		{/foreach}
		</ul>
	</section>
{/if}


