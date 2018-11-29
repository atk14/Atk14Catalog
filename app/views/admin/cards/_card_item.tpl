<tr>
	<td class="item-id">{$card->getId()}</td>
	<td class="item-title">{$card->getName()}</td>
	<td class="item-hasvariants">{$card->hasVariants()|display_bool}</td>
	<td class="item-tags">{to_sentence var=$card->getTags() words_connector=" , " last_word_connector=" , "}</td>
	<td class="item-created">{$card->getCreatedAt()|format_datetime}</td>
	<td class="item-updated">{$card->getUpdatedAt()|format_datetime}</td>
	<td class="item-actions">
		{capture assign="confirm"}{t 1=$card->getName()|h escape=no}You are about to delete the product %1.
Are you sure?{/t}{/capture}

		{dropdown_menu}
			{a action=edit id=$card _class="btn btn-default"}{icon glyph="pencil-alt"} {t}Edit{/t}{/a}
			{a_destroy id=$card _confirm=$confirm}{icon glyph="trash-alt"} {t}Delete product{/t}{/a_destroy}
		{/dropdown_menu}
	</td>
</tr>
