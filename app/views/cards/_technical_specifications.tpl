{assign technical_specifications $card->getTechnicalSpecifications()}

{if $technical_specifications}
	<table class="table">
		{foreach $technical_specifications as $ts}
			<tr>
				<th>{$ts->getKey()}</th>
				<td>{!$ts->getContent()|markdown}</td>
			</tr>
		{/foreach}
	</table>
{/if}
