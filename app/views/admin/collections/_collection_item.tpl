<li class="list-group-item" data-id="{$collection->getId()}">
	{* {$collection->getVisible()|display_bool} *}

	{render partial="shared/list_thumbnail" image=$collection->getImageUrl()}

	{$collection->getName()}
	<ul class="list-inline pull-right">
		<li>{a action=edit id=$collection}<i class="glyphicon glyphicon-edit"></i> {t}Edit{/t}{/a}</li>

		{if $collection->isDeletable()}
			{capture assign="confirm"}{t 1=$collection->getName()|h escape=no}You are about to permanently delete collection %1
Are you sure about that?{/t}{/capture}
			<li>{a_remote action=destroy id=$collection _method=post _confirm=$confirm _class="btn btn-danger btn-xs"}<i class="glyphicon glyphicon-remove"></i>{/a_remote}</li>
		{/if}
	</ul>
</li>
