<h1>{$page_title}</h1>

{render partial="shared/form"}

<hr>

{render partial="textual_sections"}

<hr>

{render partial="categories" card=$card categories=$categories form=$add_to_category_form}

<hr>

{render partial="technical_specifications"}

<hr>

{render partial="shared/image_gallery" object=$card}

<hr>

{render partial="shared/attachments" object=$card}

<hr>

{render partial="filters" card=$card categories=$categories form=$add_to_category_form}

<hr>

<h2>{t 1=$products|@count}Product variants (%1){/t}</h2>
{if !$card->hasVariants()}
	{t}U tohoto produktu se neuvažují varianty{/t} &rarr; {a action=enable_variants id=$card _method=post _confirm="Opravdu?"}{t}přepnout na variantový režim{/t}{/a}
{else}
	{render partial="products"}
	{a action="products/create_new" card_id=$card _class="btn btn-default"}<i class="glyphicon glyphicon-plus-sign"></i> {t}Přidat novou variantu{/t}{/a}
{/if}

<hr>

<h2>{t}Related products{/t}</h2>
{render partial="cards_list" type="related_cards" cards=$card->getRelatedCards() button_title="{t}Add related product{/t}" empty_list_message="{t}There is no related product{/t}"}

<hr>

<h2>{t}Consumables{/t}</h2>
{render partial="cards_list" type="consumables" cards=$card->getConsumables() button_title="{t}Add consumable{/t}" empty_list_message="{t}There are no consumables{/t}"}

<hr>

<h2>{t}Accessories{/t}</h2>
{render partial="cards_list" type="accessories" cards=$card->getAccessories() button_title="{t}Add accessory{/t}" empty_list_message="{t}There are no accessories{/t}"}
