<li class="list-group-item" data-id="{$brand->getId()}">
	<div class="d-flex justify-content-between align-items-center">
		<div>
			{render partial="shared/list_thumbnail" image=$brand->getLogoUrl()}
			{$brand->getName()}
		</div>
		<div>
			{dropdown_menu}
				{a action=edit id=$brand}{!"pencil-alt"|icon} {t}Edit{/t}{/a}

				{if $brand->isDeletable()}
					{capture assign="confirm"}{t 1=$brand->getName()|h escape=no}You are about to permanently delete brand %1
	Are you sure about that?{/t}{/capture}
					{a_destroy id=$brand _confirm=$confirm}{!"trash-alt"|icon} {t}Delete{/t}{/a_destroy}
				{/if}
			{/dropdown_menu}
		</div>
	</div>
</li>
