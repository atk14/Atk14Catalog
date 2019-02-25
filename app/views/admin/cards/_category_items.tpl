{* Kategorie sem pritecou z kontroleru - vyhazuji se tam vsechny filtry *}
{* assign var=categories value=$card->getCategories() *}

{if !$categories}

	<p>{t}The product is not yet in any category.{/t}</p>

{else}

	<ul class="list-group list-sortable" data-sortable-url="{link_to action="set_category_rank" id=$card}" data-sortable-param="category_id">
		{foreach $categories as $c}
			<li class="list-group-item" data-id="{$c->getId()}">
				{a action="categories/edit" id=$c _title="Editovat kategorii"}/{$c->getPath()}/{/a}
				{if !$c->g("visible")} <em>({t}invisible{/t})</em>{/if}

				{dropdown_menu}
				{a_destroy action="remove_from_category" id=$card category_id=$c _title="{t}Remove from the category{/t}"}<i class="glyphicon glyphicon-remove"></i> {!"remove"|icon} {t}Remove{/t}{/a_destroy}
				{/dropdown_menu}
			</li>
		{/foreach}
	</ul>

{/if}
