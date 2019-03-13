<h1>{button_create_new}{t}Add a product{/t}{/button_create_new} {$page_title}</h1>

{render partial="shared/search_form"}

{if $finder->isEmpty()}

	<p>{t}No product has been found.{/t}</p>

{else}
	<table class="table table-sm table-striped table--products">
		<thead>
			<tr class="table-dark">
				{sortable key=id}<th class="item-id">#</th>{/sortable}
				<th></th>
				{sortable key=name}<th class="item-title">{t}Name{/t}</th>{/sortable}
				{sortable key=has_variants}<th class="item-hasvariants">{t}Has variants?{/t}</th>{/sortable}
				<th class="item-tags">{t}Tags{/t}</th>
				{sortable key=created_at}<th class="item-created">{t}Created at{/t}</th>{/sortable}
				{sortable key=updated_at}<th class="item-updated">{t}Updated at{/t}</th>{/sortable}
				<th class="item-actions"></th>
			</tr>
		</thead>

		<tbody>
			{render partial="card_item" from=$finder->getRecords() item=card}
		</tbody>
	
	</table>
	{paginator}
{/if}
