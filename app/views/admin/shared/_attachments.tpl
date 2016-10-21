{*
 * Vyrenderuje seznam priloh pro dany object.
 *
 * {render partial="shared/attachments" object=$static_page}
 *}

<h2>{t}Attachments{/t}</h2>

{assign var=attachments value=Attachment::GetAttachments($object)}

{if !$attachments}
	<div class="img-message">
		<p>{t}Currently there are no attachments{/t}</p>
	</div>
{/if}

<ul class="list-group list-group-attachments list-sortable" data-sortable-url="{link_to action="attachments/set_rank"}">
	{if $attachments}
		{render partial="shared/attachment_item" from=$attachments item=attachment}
	{/if}
</ul>

<p>{a action="attachments/create_new" table_name=$object->getTableName() record_id=$object->getId() _class="btn btn-default" _id="attachmentToCard"}<i class="glyphicon glyphicon-plus-sign"></i> {t}Add an attachment{/t}{/a}</p>
