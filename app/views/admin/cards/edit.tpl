<h1>{$page_title}</h1>

{render partial="shared/form" small_form=1}

<hr>

{render partial="textual_sections"}

<hr>

{render partial="shared/image_gallery" object=$card}

<hr>

{render partial="categories" card=$card categories=$categories form=$add_to_category_form}

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
{render partial="related_cards"}
