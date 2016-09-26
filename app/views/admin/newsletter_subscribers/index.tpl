<h1>{$page_title}</h1>

{render partial="shared/search_form"}

{if $finder->isEmpty()}

	<p>{t}No record has been found.{/t}</p>

{else}

	<table class="table">
		<thead>
			<tr>
				<th>#</th>
				{sortable key=email}<th>{t}Email{/t}</th>{/sortable}
				<th>{t}Name{/t}</th>
				{sortable key=created_at}<th>{t}Subscribed since{/t}</th>{/sortable}
				<th>{t}IP address{/t}</th>
			</tr>
		</thead>
		<tbody>
			{render partial="newsletter_subscriber_item" from=$finder->getRecords() item=newsletter_subscriber}
		</tbody>		
	</table>
	{paginator}

	<p><a href="{$csv_export_url}">{t}Export emails in CSV{/t}</a></p>

{/if}
