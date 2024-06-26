<li class="list-group-item" data-id="{$store->getId()}">
		<div class="d-flex justify-content-between align-items-center">
			<div>
				{render partial="shared/list_thumbnail" image=$store->getImageUrl()}

				{$store->getName()}
			</div>
			<div class="">
				{dropdown_menu}
					{a action="edit" id=$store}{!"pencil-alt"|icon} {t}Edit{/t}{/a}
					{a namespace="" action="stores/detail" id=$store}{!"eye-open"|icon} {t}Visit public link{/t}{/a}

					{capture assign="confirm"}{t 1=$store->getName()|h escape=no}You are about to permanently delete store %1
		Are you sure about that?{/t}{/capture}
					{a_destroy id=$store _confirm=$confirm}{!"trash-alt"|icon} {t}Delete{/t}{/a_destroy}
				{/dropdown_menu}
			</div>
	</div>	
</li>
