{assign var=tech_specs value=$card->getTechnicalSpecifications()}
{capture assign=return_uri}{link_to action="cards/edit" id=$card}#technical_specifications{/capture}

<div id="technical_specifications">

	<h3>{t}Technical specifications{/t}</h3>

	{if !$tech_specs}

		<p>{t}There is no record yet.{/t}</p>

	{else}

		<ul class="list-group list-sortable" data-sortable-url="{link_to action="technical_specifications/set_rank"}">
		{foreach $tech_specs as $ts}
			<li class="list-group-item" data-id="{$ts->getId()}">
				<strong>{$ts->getKey()->g("key")}:</strong> {!$ts->getContent()|truncate:50|h}

				{dropdown_menu}
					{a action="technical_specifications/edit" id=$ts return_uri=$return_uri}{icon glyph=edit} {t}Edit value{/t}{/a}
					{a action="technical_specification_keys/edit" id=$ts->getTechnicalSpecificationKeyId() return_uri=$return_uri}{icon glyph=edit} {t}Edit key{/t}{/a}
					{a_destroy action="technical_specifications/destroy" id=$ts}{icon glyph=remove} {t}Delete{/t}{/a_destroy}
				{/dropdown_menu}
			</li>
		{/foreach}
		</ul>

	{/if}

	{render partial="add_technical_specification_form" form=$add_technical_specification_form}

</div>
