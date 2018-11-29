<li class="list-group-item" data-id="{$collection->getId()}">
	<div class="d-flex justify-content-between align-items-center">
		<div>
			{render partial="shared/list_thumbnail" image=$collection->getImageUrl()}
			{$collection->getName()}
		</div>
		<div>
			{dropdown_menu}
				{a action=edit id=$collection}{icon glyph="pencil-alt"} {t}Edit{/t}{/a}

				{if $collection->isDeletable()}
					{capture assign="confirm"}{t 1=$collection->getName()|h escape=no}You are about to permanently delete collection %1
	Are you sure about that?{/t}{/capture}
					{a_destroy id=$collection _confirm=$confirm}{icon glyph="trash-alt"} {t}Delete{/t}{/a_destroy}
				{/if}
			{/dropdown_menu}	
		</div>
	</div>
</li>
