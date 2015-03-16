<h1>{$page_title}</h1>

<p>{a action=create_new _class="btn btn-primary"}<i class="glyphicon glyphicon-plus-sign"></i> {t}Add a collection{/t}{/a}</p>

{if $collections}

	<ul class="list-group list-sortable" data-sortable-url="{link_to action="set_rank"}">
		{foreach $collections as $collection}
			{render partial="collection_item"}
		{/foreach}
	</ul>

{else}

	<p class="alert alert-info cleaner">{t}No record has been found.{/t}</p>

{/if}
