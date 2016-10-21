<h1>{button_create_new}{t}Add a collection{/t}{/button_create_new} {$page_title}</h1>

{if $collections}

	<ul class="list-group list-sortable" data-sortable-url="{link_to action="set_rank"}">
		{foreach $collections as $collection}
			{render partial="collection_item"}
		{/foreach}
	</ul>

{else}

	<p class="alert alert-info cleaner">{t}No record has been found.{/t}</p>

{/if}
