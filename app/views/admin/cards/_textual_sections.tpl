<h3>{button_create_new action="card_sections/create_new" card_id=$card}{t}Create a new section{/t}{/button_create_new} {t}Textual sections{/t}</h3>
{assign var=sections value=$card->getCardSections()}
{if !$sections}

	<p>{t}Currently there is no section{/t}</p>

{else}

	<ul class="list-group list-sortable" data-sortable-url="{link_to action="card_sections/set_rank"}">
	{foreach $sections as $section}
		<li class="list-group-item" data-id="{$section->getId()}">
			<strong>{$section->getCardSectionType()}:</strong> {$section->getName()}
			{a action="card_sections/edit" id=$section}{/a} [{t}attachments{/t}: {$section->getAttachments()|count}, {t}images{/t}: {$section->getImages()|count}]

			{dropdown_menu}
				{a action="card_sections/edit" id=$section}{icon glyph="edit"} {t}Edit{/t}{/a}

				{capture assign="confirm"}{t 1=$section->getName()|h escape=no}You are about to delete section %1
Are you sure?{/t}{/capture}
				{a_destroy action="card_sections/destroy" id=$section _confirm=$confirm}{icon glyph="remove"} {t}Remove{/t}{/a_destroy}
			{/dropdown_menu}	
		</li>
	{/foreach}
	</ul>

{/if}
