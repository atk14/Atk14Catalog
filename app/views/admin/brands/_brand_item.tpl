<li class="list-group-item" data-id="{$brand->getId()}">
	{* {$brand->getVisible()|display_bool} *}

	{render partial="shared/list_thumbnail" image=$brand->getLogoUrl()}

	{$brand->getName()}

	<div class="pull-right">
		{dropdown_menu}
			{a action=edit id=$brand}{icon glyph=edit} {t}Edit{/t}{/a}

			{if $brand->isDeletable()}
				{capture assign="confirm"}{t 1=$brand->getName()|h escape=no}You are about to permanently delete brand %1
Are you sure about that?{/t}{/capture}
				{a_destroy id=$brand _confirm=$confirm}{icon glyph=remove} {t}Delete{/t}{/a_destroy}
			{/if}
		{/dropdown_menu}
	</div>

</li>
