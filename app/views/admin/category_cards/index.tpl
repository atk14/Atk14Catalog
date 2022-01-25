<h1>{button_create_new category_id=$category}{/button_create_new} {$page_title}</h1>

<table class="table">

<thead>
	<tr>
		<th></th>
		<th>{t}Catalog number{/t}</th>
		<th>{t}Name{/t}</th>
		<th>{t}Ranking{/t}</th>
		<th></th>
	</tr>
</thead>
	
	<tbody>
		{render partial="card_item" from=$finder->getRecords() item=card}
	</tbody>

</table>

{paginator}
