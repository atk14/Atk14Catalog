<h1>{$page_title}</h1>

<p>{a action=create_new _class="btn btn-primary"}<i class="glyphicon glyphicon-plus-sign"></i> {t}Add new static page{/t}{/a}</p>

{if !$root_static_pages}
	<p>{t}The list is empty.{/t}</p>
{else}

	<ul class="list-group list-sortable" data-sortable-url="{link_to action="static_pages/set_rank"}">
		{render partial="static_page_item" from=$root_static_pages item=static_page}
	</ul>
{/if}
