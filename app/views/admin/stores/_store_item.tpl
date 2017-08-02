<li class="list-group-item" data-id="{$store->getId()}">

	{render partial="shared/list_thumbnail" image=$store->getImageUrl()}

	{$store->getName()}
	
	<div class="pull-right">
		{dropdown_menu}
			{a action="edit" id=$store}{icon glyph="edit"} {t}Edit{/t}{/a}

			{capture assign="confirm"}{t 1=$store->getName()|h escape=no}You are about to permanently delete store %1
Are you sure about that?{/t}{/capture}
			{a_destroy id=$store _confirm=$confirm}{icon glyph=remove} {t}Delete{/t}{/a_destroy}
		{/dropdown_menu}
	</div>

</li>
