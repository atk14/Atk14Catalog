<h1>{button_create_new}{t}Add new static page{/t}{/button_create_new} {$page_title}</h1>

{if !$root_static_pages}
	<p>{t}The list is empty.{/t}</p>
{else}

	<ul class="list-group list-sortable" data-sortable-url="{link_to action="static_pages/set_rank"}">
		{render partial="static_page_item" from=$root_static_pages item=static_page}
	</ul>
{/if}
