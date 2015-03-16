<h1>{$page_title}</h1>

<p class="pull-left">{a action=create_new _class="btn btn-primary"}<i class="glyphicon glyphicon-plus-sign"></i> {t}Add a product{/t}{/a}</p>

{form _class="form-inline pull-right"}
	{!$form.search}
	<button type="submit" class="btn btn-default">{t}Search products{/t}</button>
{/form}
<hr class="cleaner">

{if $finder->isEmpty()}

	{message}{t}No product has been found.{/t}{/message}

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
