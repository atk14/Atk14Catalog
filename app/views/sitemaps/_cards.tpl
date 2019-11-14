{if $cards}
	<ul class="list--tree">
		{foreach $cards as $card}
			<li>
				{a action="cards/detail" id=$card}{$card->getName()}{/a}
			</li>
		{/foreach}
	</ul>
{/if}
