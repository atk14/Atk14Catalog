<li class="list-group-item" data-id="{$brand->getId()}">
	{* {$brand->getVisible()|display_bool} *}

	{render partial="shared/list_thumbnail" image=$brand->getLogoUrl()}

	{$brand->getName()}
	<ul class="list-inline pull-right">
		<li>{a action=edit id=$brand}<i class="glyphicon glyphicon-edit"></i> {t}Edit{/t}{/a}</li>

		{if $brand->isDeletable()}
			{capture assign="confirm"}{t 1=$brand->getName()|h escape=no}You are about to permanently delete brand %1
Are you sure about that?{/t}{/capture}
			<li>{a_remote action=destroy id=$brand _method=post _confirm=$confirm _class="btn btn-danger btn-xs"}<i class="glyphicon glyphicon-remove"></i>{/a_remote}</li>
		{/if}
	</ul>
</li>
