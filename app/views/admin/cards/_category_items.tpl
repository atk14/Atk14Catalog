{* Kategorie sem pritecou z kontroleru - vyhazuji se tam vsechny filtry *}
{* assign var=categories value=$card->getCategories() *}

{if !$categories}
	{message}{t}Produkt zatím není zařazen v žádné kategorii.{/t}{/message}
{else}
	<ul class="list-group">
		{foreach $categories as $c}
			<li class="list-group-item">
				{a action="categories/edit" id=$c _title="Editovat kategorii"}/{$c->getPath()}/{/a}
				{a_destroy action="remove_from_category" id=$card category_id=$c _class="confirm btn btn-danger btn-xs pull-right" _title="Smazat z kategorie"}<i class="glyphicon glyphicon-remove"></i> <span class="hide">{t}Smazat{/t}</span>{/a_destroy}
			</li>
		{/foreach}
	</ul>
{/if}
