<h1>{button_create_new}{t}Create new category tree{/t}{/button_create_new} {$page_title}</h1>

<ul class="list-group list-group-flush list-sortable" data-sortable-url="{link_to action="set_rank"}">
	{foreach $roots as $root}
		<li class="list-group-item" data-id="{$root->getId()}">
			<div class="d-flex justify-content-between align-items-center">
				<div>
					{$root->getName()}
					{if !$root->isVisible()}<em>( {!"eye-slash"|icon} {t}invisible{/t})</em>{/if}
				</div>
				<div>
					{dropdown_menu}
					{a action="detail" id=$root}{!"folder-open"|icon} {t}Detail{/t}{/a}
					{if $root->isDeletable()}
							{capture assign="confirm"}{t 1=$root->getName()|h escape=no}You are about to delete the catalog tree %1.
		Are you sure?{/t}{/capture}
							{a_destroy action=destroy id=$root _method=post _confirm=$confirm}{!"trash-alt"|icon} {t}Delete{/t}{/a_destroy}
					{/if}
					{/dropdown_menu}
				</div>
			</div>
		</li>
	{/foreach}
</ul>
