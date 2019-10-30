<h1>{$page_title}</h1>

{render partial="shared/form"}

<hr>

{render partial="textual_sections"}

<hr>

{render partial="categories" card=$card categories=$categories form=$add_to_category_form}

<hr>

{render partial="variants"}

<hr>

{render partial="filters" card=$card categories=$categories form=$add_to_category_form}

<hr>

{render partial="technical_specifications"}

<hr>

{render partial="shared/image_gallery" object=$card}

<hr>

{render partial="shared/attachments" object=$card}

<hr>

{render partial="cards_list" type="related_cards" cards=$card->getRelatedCards() title="{t}Related products{/t}" button_title="{t}Add related product{/t}" empty_list_message="{t}There is no related product{/t}"}

<hr>

{render partial="cards_list" type="consumables" cards=$card->getConsumables() title="{t}Consumables{/t}" button_title="{t}Add consumable{/t}" empty_list_message="{t}There are no consumables{/t}"}

<hr>

{render partial="cards_list" type="accessories" cards=$card->getAccessories() title="{t}Accessories{/t}" button_title="{t}Add accessory{/t}" empty_list_message="{t}There are no accessories{/t}"}
