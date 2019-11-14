{if $cards}
	<ul class="list--tree">
		{foreach $cards as $card}
			<li>
				{a action="cards/detail" id=$card _with_hostname=1}{$card->getName()}{/a}
			</li>
		{/foreach}
	</ul>
{/if}
