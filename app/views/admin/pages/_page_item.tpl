<li class="list-group-item" data-id="{$page->getId()}">
	{$page->getTitle()}

	{a namespace="" controller=pages action=detail id=$page}{t 1=$lang}odkaz [%1]{/t}{/a}

	{foreach $secondary_langs as $sl}
		{a namespace="" controller=pages action=detail id=$page lang=$sl}{t 1=$sl}[%1]{/t}{/a}
	{/foreach}

	<div class="pull-right">
		{dropdown_menu}
		{a action=edit id=$page}<span class="glyphicon glyphicon-edit"></span> {t}Edit{/t}{/a}

		{if true || $page->isDeletable()}
			{capture assign="confirm"}{t 1=$page->getTitle()|h escape=no}You are about to delete page %1
Are you sure?{/t}{/capture}
			{a_remote action=destroy id=$page _method=post _confirm=$confirm}<span class="glyphicon glyphicon-remove"></span> {t}Delete page{/t}{/a_remote}
		{/if}
		{/dropdown_menu}
	</div>


	{if $page->getChildPages()}
		<div class="clearfix"><br></div>
		<ul class="list-group list-sortable" data-sortable-url="{link_to action="pages/set_rank"}">
			{render partial="page_item" from=$page->getChildPages() item=page}
		</ul>
	{else}	
		<div class="clearfix"></div>
	{/if}
</li>
