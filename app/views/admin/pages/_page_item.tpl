<li class="list-group-item{if $page->getChildPages()} has-child{/if}" data-id="{$page->getId()}">
	{$page->getTitle()}

	{a namespace="" controller=pages action=detail id=$page}{t 1=$lang}odkaz [%1]{/t}{/a}

	{foreach $secondary_langs as $sl}
		{a namespace="" controller=pages action=detail id=$page lang=$sl}{t 1=$sl}[%1]{/t}{/a}
	{/foreach}

	<div class="pull-right">
		{a action=edit id=$page}<span class="glyphicon glyphicon-edit"></span> {t}Edit{/t}{/a}

		{if true || $page->isDeletable()}
			{capture assign="confirm"}{t 1=$page->getTitle()|h escape=no}Chystáte se smazat stránku %1
Jste si jistý/á?{/t}{/capture}
			{a_remote action=destroy id=$page _method=post _confirm=$confirm _title="{t}Smazat stránku{/t}" _class="btn btn-danger btn-xs"}<span class="glyphicon glyphicon-remove"></span>{/a_remote}
		{/if}
	</div>

	{if $page->getChildPages()}
		<ul class="list-group">
			{render partial="page_item" from=$page->getChildPages() item=page}
		</ul>
	{/if}
</li>
