<h1>{$page_title}</h1>

<ul>
	<li>
		<h3>{a action="categories/edit" id=$root}{$root->getName()}{/a}{if !$root->g("visible")} <small><em>({t}invisible{/t})</em></small>{/if}</h3>
		{render partial=categories categories=$root->getChildCategories()}
	</li>
</ul>
