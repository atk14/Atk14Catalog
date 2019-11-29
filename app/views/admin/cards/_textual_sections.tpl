{capture assign=return_uri}{$request->getRequestUri()}#textual_sections{/capture}

<h3 id="textual_sections">{button_create_new action="card_sections/create_new" card_id=$card return_to_anchor=textual_sections}{t}Create a new section{/t}{/button_create_new} {t}Textual sections{/t}</h3>
{assign var=sections value=$card->getCardSections()}
{if !$sections}

	<p>{t}Currently there is no section{/t}</p>

{else}

	<ul class="list-group list-sortable" data-sortable-url="{link_to action="card_sections/set_rank"}">
	{foreach $sections as $section}
		<li class="list-group-item" data-id="{$section->getId()}">
			{dropdown_menu clearfix=false}
				{a action="card_sections/edit" id=$section return_uri=$return_uri}{icon glyph="edit"} {t}Edit{/t}{/a}

				{capture assign="confirm"}{t 1=$section->getName()|h escape=no}You are about to delete section %1
Are you sure?{/t}{/capture}
				{a_destroy action="card_sections/destroy" id=$section _confirm=$confirm}{icon glyph="remove"} {t}Remove{/t}{/a_destroy}
			{/dropdown_menu}

			<strong>{if $section->getName()}{$section->getName()}{else}<em>{t}unnamed{/t}</em>{/if}</strong><br>
			<span title="{t}content type{/t}">{$section->getCardSectionType()}</span>, {t}attachments{/t}: {$section->getAttachments()|count}, {t}images{/t}: {$section->getImages()|count}<br>
			{if $section->getBody()}
				<small>{$section->getBody()|truncate:200}</small>
			{else}
				<small><em>{t}no textual content{/t}</em></small>
			{/if}
		</li>
	{/foreach}
	</ul>

{/if}
