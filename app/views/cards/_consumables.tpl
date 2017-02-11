{assign var=consumables value=$card->getConsumables()}

{if $consumables}
	<hr>
	<h3>{t}Consumables{/t}</h3>
	<ul>
		{foreach $consumables as $c}
			<li>
				{a action="cards/detail" id=$c}
					{$c->getName()}
				{/a}
			</li>
		{/foreach}
	</ul>
{/if}
