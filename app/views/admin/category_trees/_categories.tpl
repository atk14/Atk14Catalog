{if $categories}
	<ul>
		{foreach $categories as $c}
			<li>
				{if $c->isFilter()}<em>filtr:</em>{/if}
				{if $c->isPointingToCategory()}<em>alias:</em>{/if}

				{a action="categories/edit" id=$c}{$c->getName()}{/a}

				{if !$c->g("visible")} <em>(invisible)</em>{/if}

				{render partial=categories categories=$c->getChildCategories()}
			</li>
		{/foreach}
	</ul>
{/if}
