<h2>{t}Technical specifications{/t}</h2>

{assign var=tech_specs value=$card->getTechnicalSpecifications()}

{if !$tech_specs}

	<p>{t}There is no record yet.{/t}</p>

{else}

	<ul class="list-group list-sortable" data-sortable-url="{link_to action="technical_specifications/set_rank"}">
	{foreach $tech_specs as $ts}
		<li class="list-group-item" data-id="{$ts->getId()}">
			<div class="pull-right">
			{dropdown_menu}
				{a action="technical_specifications/edit" id=$ts}{icon glyph=edit} {t}Edit{/t}{/a}
				{a_destroy action="technical_specifications/destroy" id=$ts}{icon glyph=remove} {t}Delete{/t}{/a_destroy}
			{/dropdown_menu}
			</div>

			<strong>{$ts->getKey()}:</strong><br>
			{!$ts->getContent()|h|nl2br}
		</li>
	{/foreach}
	</ul>

{/if}

<p>{a action="technical_specifications/create_new" card_id=$card _class="btn btn-default"}<i class="glyphicon glyphicon-plus-sign"></i> {t}Add specification{/t}{/a}</p>
