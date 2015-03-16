<tr>
	<td>{$card->getId()}</td>
	<td>{$card->getName()}</td>
	<td>{$card->hasVariants()|display_bool}</td>
	<td>{to_sentence var=$card->getTags() words_connector=" , " last_word_connector=" , "}</td>
	<td>{$card->getCreatedAt()|format_datetime}</td>
	<td>{$card->getUpdatedAt()|format_datetime}</td>
	<td>
		<div class="btn-group btn-group-sm">
			{a action=edit id=$card _class="btn btn-default"}<i class="glyphicon glyphicon-edit"></i> {t}Edit{/t}{/a}
			<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
				<span class="caret"></span>
				<span class="sr-only">{t}Zobrazit nabídku{/t}</span>
			</button>
			<ul class="dropdown-menu dropdown-menu-right">
				{capture assign="confirm"}{t 1=$card->getName()|h escape=no}Chystáte se smazat produkt %1 Jste si jistý?{/t}{/capture}
				<li>{a_destroy id=$card _confirm=$confirm}<i class="glyphicon glyphicon-remove"></i> {t}Smazat produkt{/t}{/a_destroy}</li>
			</ul>
		</div>
	</td>
</tr>
