{assign technical_specifications $card->getTechnicalSpecifications(["visible" => true])}

{if $technical_specifications}
	<table class="table table-sm">
		{foreach $technical_specifications as $ts}
			<tr>
				<th>{$ts->getKey()}</th>
				<td>{!$ts->getContent()|markdown}</td>
			</tr>
		{/foreach}
	</table>
{/if}
