<h2>Filtry</h2>
{if $filters}
	<ul>
		{foreach $filters as $f}
			<li>/{$f.filter->getPath()}/:
				{foreach $f.items as $c}
					{$c->getName()}{if !$c@last}, {/if}
				{/foreach}
			</li>
		{/foreach}
	</ul>
{else}
	<p>{t}Tento produkt není zařazen v žádném filtru.{/t}</p>
{/if}
{a action="card_filters/edit" id=$card _class="btn btn-default"}{t}Nastavit filtry{/t} ({$filter_categories_count}){/a}
