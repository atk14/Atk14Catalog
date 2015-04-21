{*
 * Tato sablonka se vyrenderuje do jsonu pri XHR uploadu obrazku do adminu (viz controllers/admin/attachments_controller.php)
 *}
<li class="list-group-item media clearfix" data-id="{$attachment->getId()}">
	<div class="btn-group pull-right">
		{capture assign="title"}{t}Odpojit přílohu{/t}{/capture}
		{a_destroy action="attachments/destroy" id=$attachment _title=$title _class="confirm btn btn-danger btn-xs pull-right"}<span class="glyphicon glyphicon-remove"></span>{/a_destroy}
	</div>

	<a href="{link_to action="attachments/edit" id=$attachment}" title="{t}Editovat tuto přílohu{/t}" class="pull-left">{$attachment->getName()} ({$attachment->getFilename()})</a>

	<div class="media-body">
		{if $attachment->getName()}
			<h4 class="media-heading">{$attachment->getName()}</h4>
		{/if}

		{if $attachment->getDescription()}
			<p>{$attachment->getDescription()}</p>
		{/if}
	</div>
</li>
