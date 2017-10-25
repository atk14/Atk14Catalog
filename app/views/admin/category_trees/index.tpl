<h1>{button_create_new}{t}Create new category tree{/t}{/button_create_new} {$page_title}</h1>

<ul class="list-group list-sortable" data-sortable-url="{link_to action="set_rank"}">
	{foreach $roots as $root}
		<li class="list-group-item" data-id="{$root->getId()}">
			{$root->getName()}
			{if !$root->isVisible()}<em>({t}invisible{/t})</em>{/if}
			{dropdown_menu}
			{a action="detail" id=$root}{icon glyph="edit"} {t}Edit{/t}{/a}
			{if $root->isDeletable()}
					{capture assign="confirm"}{t 1=$root->getName()|h escape=no}You are about to delete the catalog tree %1.
Are you sure?{/t}{/capture}
					{a_destroy action=destroy id=$root _method=post _confirm=$confirm}{icon glyph="remove"} {t}Delete{/t}{/a_destroy}
			{/if}
			{/dropdown_menu}
		</li>
	{/foreach}
</ul>
