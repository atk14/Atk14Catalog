<h1>{button_create_new}{t}Add a product{/t}{/button_create_new} {$page_title}</h1>

{render partial="shared/search_form"}

{if $finder->isEmpty()}

	<p>{t}No product has been found.{/t}</p>

{else}
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				{sortable key=name}<th>{t}Name{/t}</th>{/sortable}
				{sortable key=has_variants}<th>{t}Has variants?{/t}</th>{/sortable}
				<th>{t}Tags{/t}</th>
				{sortable key=created_at}<th>{t}Created at{/t}</th>{/sortable}
				{sortable key=updated_at}<th>{t}Updated at{/t}</th>{/sortable}
				<th></th>
			</tr>
		</thead>

		<tbody>
			{render partial="card_item" from=$finder->getRecords() item=card}
		</tbody>
	
	</table>
	{paginator}
{/if}
