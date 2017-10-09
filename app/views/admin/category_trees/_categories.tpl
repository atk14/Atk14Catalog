{if $categories}
	<ul>
		{foreach $categories as $c}
			<li>
				{if $c->isFilter()}<em>{t}filter{/t}:</em>{/if}
				{if $c->isPointingToCategory()}<em>{t}alias{/t}:</em>{/if}

				{a action="categories/edit" id=$c}{$c->getName()}{/a}

				{if !$c->g("visible")} <em>({t}invisible{/t})</em>{/if}

				{render partial=categories categories=$c->getChildCategories()}
			</li>
		{/foreach}
	</ul>
{/if}
