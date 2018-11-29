<h1>{button_create_new}{t}Add a store{/t}{/button_create_new} {$page_title}</h1>


{if $stores}

	<ul class="list-group list-group-flush list-sortable" data-sortable-url="{link_to action="set_rank"}">
		{foreach $stores as $store}
			{render partial="store_item"}
		{/foreach}
	</ul>

{else}

	<p>{t}No record has been found.{/t}</p>

{/if}
