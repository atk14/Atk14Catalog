{* Kategorie sem pritecou z kontroleru - vyhazuji se tam vsechny filtry *}
{* assign var=categories value=$card->getCategories() *}

{if !$categories}

	<p>{t}The product is not yet in any category.{/t}</p>

{else}

	<ul class="list-group">
		{foreach $categories as $c}
			<li class="list-group-item">
				{a action="categories/edit" id=$c _title="Editovat kategorii"}/{$c->getPath()}/{/a}
				{a_destroy action="remove_from_category" id=$card category_id=$c _class="confirm btn btn-danger btn-xs pull-right" _title="{t}Remove from the category{/t}"}<i class="glyphicon glyphicon-remove"></i> <span class="hide">{t}Remove{/t}</span>{/a_destroy}
			</li>
		{/foreach}
	</ul>

{/if}
