{*
 * Tato sablonka se vyrenderuje do jsonu pri XHR uploadu obrazku do adminu (viz controllers/admin/attachments_controller.php)
 *}
<li class="list-group-item media clearfix" data-id="{$attachment->getId()}">
	<div class="media-body">
			<ul class="list-inline pull-right">
				<li>{a action="attachments/edit" id=$attachment}<i class="glyphicon glyphicon-edit"></i> {t}Edit{/t}{/a}</li>

				{capture assign="confirm"}{t 1=$attachment->getName()|h escape=no}You are about to delete the attachment %1
Are you sure about that?{/t}{/capture}
				<li>{a_destroy action="attachments/destroy" id=$attachment _method=post _confirm=$confirm _class="btn btn-danger btn-xs"}<i class="glyphicon glyphicon-remove"></i>{/a_destroy}</li>
			</ul>
			<a href="{$attachment->getUrl()}">{$attachment->getName()} ({if $attachment->getName()!=$attachment->getFilename()}{$attachment->getFilename()}, {/if}{$attachment->getFilesize()|format_bytes})</a>
	</div>

</li>
