<tr>
	<td>{render partial="shared/list_thumbnail" image=$card->getImage()}</td>
	<td>{render partial="shared/product_codes" products=$card->getProducts(["visible" => null])}</td>
	<td>{$card->getName()}</td>
	<td>{$finder->getOffset()+$__index__+1}</td>
	<td>
		{dropdown_menu}
			{a action="edit" category_id=$category card_id=$card->getId() rank=$finder->getOffset()+$__index__+1}{icon glyph=edit} {t}Edit ranking{/t}{/a}
			{a_destroy category_id=$category card_id=$card->getId()}{icon glyph=remove} {t}Remove{/t}{/a_destroy}
		{/dropdown_menu}
	</td>
</tr>
