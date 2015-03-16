<p>
	{if $url_back}
		<a href="{$url_back}" class="btn btn-default"><i class="glyphicon glyphicon-chevron-left"></i> {t}Zpět{/t}</a>
	{/if}
	{a action=create_new table_name=$table_name record_id=$record_id _class="btn btn-primary"}<i class="glyphicon glyphicon-plus-sign"></i> {t}Přidat obrázek{/t}{/a}
</p>

{if !$images}

	<p>Momentálně tady nejsou žádné obrázky.</p>

{else}

	<ul class="list-group list-sortable" data-sortable-url="{link_to action="set_rank"}">
		{render partial="image_item" from=$images item=image}
	</ul>

{/if}
