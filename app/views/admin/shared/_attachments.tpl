{*
 * Vyrenderuje seznam priloh pro dany object.
 *
 * {render partial="shared/attachments" object=$page}
 *}

{assign var=attachments value=Attachment::GetAttachments($object)}

<h2>{button_create_new action="attachments/create_new" table_name=$object->getTableName() record_id=$object->getId()}{t}Add an attachment{/t}{/button_create_new}{t}Attachments{/t}</h2>

{if !$attachments}
	<div class="img-message">
		<p>{t}Currently there are no attachments.{/t}</p>
	</div>
{/if}

<ul class="list-group list-group-attachments list-sortable" data-sortable-url="{link_to action="attachments/set_rank"}">
	{if $attachments}
		{render partial="shared/attachment_item" from=$attachments item=attachment}
	{/if}
</ul>
