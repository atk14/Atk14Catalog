	{dropdown_menu pull="none"}
		{a action=edit id=$page}<i class="fas fa-pencil-alt"></i> {t}Edit{/t}{/a}
		{a namespace="" action="pages/detail" id=$page} <i class="fas fa-eye"></i> {t}Visit public link{/t}{/a}

		{if $page->isDeletable()}
			{capture assign="confirm"}{t 1=$page->getTitle()|h escape=no}You are about to delete page %1
Are you sure?{/t}{/capture}
			{a_remote action=destroy id=$page _method=post _confirm=$confirm}<i class="fas fa-trash-alt"></i> {t}Delete page{/t}{/a_remote}
		{/if}
	{/dropdown_menu}
