{if $categories}
	<ul class="list--tree">
		{foreach $categories as $c}
			<li>
				{if $c->isFilter()}<em>{!"filter"|icon} {t}filter{/t}:</em>{/if}
				{if $c->isPointingToCategory()}<em>{!"share-alt"|icon} {t}link{/t}:</em>{/if}
				
				{if !$c->isPointingToCategory() and !$c->isFilter()}<em>{!"folder-open"|icon}</em>{/if}

				{a action="categories/edit" id=$c}{$c->getName()}{/a}

				{if !$c->g("visible")} <em>{!"eye-slash"|icon} ({t}invisible{/t})</em>{/if}

				{render partial=categories categories=$c->getChildCategories()}
			</li>
		{/foreach}
	</ul>
{/if}
