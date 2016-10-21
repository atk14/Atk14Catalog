<h2>{t}Textual sections{/t}</h2>
{assign var=sections value=$card->getCardSections()}
{if !$sections}
	<p>{t}U totoho produktu zatím není žádná sekce{/t}</p>
{else}
	<ul class="list-group list-sortable" data-sortable-url="{link_to action="card_sections/set_rank"}">
	{foreach $sections as $section}
		<li class="list-group-item" data-id="{$section->getId()}">
			<strong>{$section->getCardSectionType()}:</strong> {$section->getName()}
			{a action="card_sections/edit" id=$section}{/a} [Přílohy: {$section->getAttachments()|count}, Obrázky: {$section->getImages()|count}]
			<ul class="list-inline pull-right">
				<li>{a action="card_sections/edit" id=$section}<i class="glyphicon glyphicon-edit"></i> {t}Edit{/t}{/a}</li>

				{capture assign="confirm"}{t 1=$section->getName()|h escape=no}Chystáte se smazat sekci %1
Jste si jistý?{/t}{/capture}
				<li>{a_remote action="card_sections/destroy" id=$section _method=post _confirm=$confirm _class="btn btn-danger btn-xs"}<i class="glyphicon glyphicon-remove"></i>{/a_remote}</li>
			</ul>
		</li>
	{/foreach}
	</ul>
{/if}
<p>{a action="card_sections/create_new" card_id=$card _class="btn btn-default"}<i class="glyphicon glyphicon-plus-sign"></i> {t}Přidat novou sekci{/t}{/a}</p>
