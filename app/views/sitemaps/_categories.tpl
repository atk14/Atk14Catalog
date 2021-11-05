{if $categories}
<ul class="list--tree">

{foreach $categories as $category}

	{if $category->isVisible() && !$category->isFilter() && !$category->isAlias()}

	<li>
		{a action="categories/detail" path=$category->getPath() _with_hostname=1}{$category->getName()}{/a}
		<p>
			{$category->getTeaser()|markdown|strip_html|truncate:200}
		</p>
		{render partial="categories" categories=$category->getChildCategories()}
	</li>

	{/if}

{/foreach}

</ul>
{/if}
