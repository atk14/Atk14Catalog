	{dropdown_menu}
		{a action=edit id=$page}{icon glyph="edit"} {t}Edit{/t}{/a}
		{a namespace="" action="pages/detail" id=$page}{icon glyph="eye-open"} {t}Visit public link{/t}{/a}

		{if $page->isDeletable()}
			{capture assign="confirm"}{t 1=$page->getTitle()|h escape=no}You are about to delete page %1
Are you sure?{/t}{/capture}
			{a_remote action=destroy id=$page _method=post _confirm=$confirm}<span class="glyphicon glyphicon-remove"></span> {t}Delete page{/t}{/a_remote}
		{/if}
	{/dropdown_menu}
