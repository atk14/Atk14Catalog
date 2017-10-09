<h2 id="filters">{t}Filters{/t}</h2>
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

	<p>{t}This product is not included in any filter{/t}</p>

{/if}
{a action="card_filters/edit" id=$card _class="btn btn-default"}{t}Set up filters{/t} ({$filter_categories_count}){/a}
