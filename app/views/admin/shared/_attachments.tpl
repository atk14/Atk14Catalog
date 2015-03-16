{*
 * Vyrenderuje seznam priloh pro dany object.
 *
 * {render partial="shared/attachments" object=$static_page}
 *}

{assign var=attachments value=Attachment::GetAttachments($object)}
{if $attachments}
	<h3>{t}Přílohy{/t}</h3>
	<ul>
		{foreach $attachments as $attachment}
			<li>
				<a href="{$attachment->getUrl()}">{$attachment->getName()}</a>
				[{$attachment->getMimeType()}, {$attachment->getFilesize()|format_bytes}]
			</li>
		{/foreach}
	</ul>
{/if}
