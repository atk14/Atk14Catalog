<tr>
	<td class="item-id">{$card->getId()}</td>
	<td class="item-thumbnail">{render partial="shared/list_thumbnail" image=$card->getImage()}</td>
	<td class="item-title">
		{highlight_search_query}{$card->getName()}{/highlight_search_query}
		{if !$card->isVisible()}
		<br><em>({!"eye-slash"|icon} {t}invisible{/t})</em>
		{/if}
	</td>
	<td class="item-hasvariants">{$card->hasVariants()|display_bool}</td>
	<td class="item-tags">{highlight_search_query}{to_sentence var=$card->getTags() words_connector=" , " last_word_connector=" , "}{/highlight_search_query}</td>
	<td class="item-created">{$card->getCreatedAt()|format_datetime}</td>
	<td class="item-updated">{$card->getUpdatedAt()|format_datetime}</td>
	<td class="item-actions">
		{capture assign="confirm"}{t 1=$card->getName()|h escape=no}You are about to delete the product %1.
Are you sure?{/t}{/capture}

		{dropdown_menu}
			{a action=edit id=$card}{!"pencil-alt"|icon} {t}Edit{/t}{/a}
			{a namespace="" action="cards/detail" id=$card}{!"eye"|icon} {t}Show on web{/t}{/a}
			{a_destroy id=$card _confirm=$confirm}{!"trash-alt"|icon} {t}Delete product{/t}{/a_destroy}
		{/dropdown_menu}
	</td>
</tr>
