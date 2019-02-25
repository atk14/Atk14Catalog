{if sizeof($categories)>1}
	<section>
	<h4>{t}Product locations{/t}</h4>
	<ul class="list list-unstyled">
	{foreach $categories as $category}
		<li>
			{foreach $category->getPathOfCategories() as $c}
				<a href="{link_to action="categories/detail" path=$c->getPath()}">{$c->getName()}</a>
				{if !$c@last}&raquo;{/if}
			{/foreach}
		</li>
	{/foreach}
	</ul>
	</section>
{/if}
