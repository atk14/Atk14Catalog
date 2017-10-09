{*
 * Tato sablonka se vyrenderuje do jsonu pri XHR uploadu obrazku do adminu (viz controllers/admin/attachments_controller.php)
 *}
<li class="list-group-item" data-id="{$attachment->getId()}">
			<a href="{$attachment->getUrl()}">{$attachment->getName()} ({if $attachment->getName()!=$attachment->getFilename()}{$attachment->getFilename()}, {/if}{$attachment->getFilesize()|format_bytes})</a>

			{dropdown_menu}
				{a action="attachments/edit" id=$attachment}{icon glyph="edit"} {t}Edit{/t}{/a}

				{capture assign="confirm"}{t 1=$attachment->getName()|h escape=no}You are about to delete the attachment %1
Are you sure about that?{/t}{/capture}
				{a_destroy action="attachments/destroy" id=$attachment _method=post _confirm=$confirm}{icon glyph=remove} {t}Remove{/t}{/a_destroy}
			{/dropdown_menu}

</li>
