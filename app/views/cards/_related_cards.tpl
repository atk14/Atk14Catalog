{assign var=related_cards value=$card->getRelatedCards()}
{if $related_cards}
	<ul>
		{foreach $related_cards as $rcard}
			<li>
			{a action="cards/detail" id=$rcard}
				{$rcard->getName()}
			{/a}
			</li>
		{/foreach}
	</ul>
{else}
	{message}{t}Nejsou určené související produkty{/t}{/message}
{/if}
