<h1>{$page_title}</h1>

<ul class="list--categories">
	<li>
		<h3>{a action="categories/edit" id=$root}{!"folder-open"|icon} {$root->getName()}{/a}{if !$root->g("visible")} <small><em>({!"eye-slash"|icon} {t}invisible{/t})</em></small>{/if}</h3>
		{render partial=categories categories=$root->getChildCategories()}
	</li>
</ul>
