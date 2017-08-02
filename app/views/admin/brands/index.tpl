<h1>{button_create_new}{t}Add a new brand{/t}{/button_create_new} {$page_title}</h1>

{if $brands}

	<ul class="list-group list-sortable" data-sortable-url="{link_to action="set_rank"}">
		{foreach $brands as $brand}
			{render partial="brand_item"}
		{/foreach}
	</ul>

{else}

	<p>{t}No record has been found.{/t}</p>

{/if}
