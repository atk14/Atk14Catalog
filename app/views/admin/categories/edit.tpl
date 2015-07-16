<h1>{$page_title}</h1>

{assign var=parent value=$category->getParentCategory()}

<p>{a action=move_to_category id=$category _class="btn btn-primary"}<i class="glyphicon glyphicon-transfer"></i> {t}Přesunout kategorii{/t}{/a}
{a action=create_alias id=$category _class="btn btn-primary"}<i class="glyphicon glyphicon-share"></i> {t}Vytvořit alias{/t}{/a}</p>

<table class="table">
	<tbody>
	<tr>
		<th>#</th><td>{$category->getId()}</td>
	</tr>
	<tr>	
		<th>{t}Cesta{/t}</th><td>/{$category->getPath()}/</td>
	</tr>

	{* Zatim to skryjeme - na viditelnost stejne nehrajeme
	<tr>
		<th>{t}Je kategorie viditelná?{/t}</th>
		<td>{$category->isVisible()|display_bool}</td>
	</tr> *}

	<tr>
		<th>{t}Je to filtr?{/t}</th>
		<td>{$category->isFilter()|display_bool}</td>
	</tr>

	<tr>
		<th>{t}Lze vkládat produkty?{/t}</th>
		<td>{$category->allowProducts()|display_bool}</td>
	</tr>
	</tbody>
</table>

{form}
	<fieldset>
		{render partial="shared/form_field" fields=$form->get_field_keys()}

		<div class="form-group">
			<button type="submit" class="btn btn-primary">{$form->get_button_text()}</button>

			{if $category->isDeletable()}
			{capture assign=confirmation}{t name=$category->getName() escape=no}Chystáte se smazat kategorii %1!
Budou smazány i všechny její podkategorie. Smazání se nedá vrátit zpět.

Opravdu to chcete?{/t}{/capture}
			{a_destroy id=$category _class="btn btn-danger" _confirm=$confirmation}{t}Smazat{/t}{/a_destroy}
			{/if}
		</div>
	</fieldset>
{/form}

<hr>

{if $category->isAlias()}

	{assign var=ptc value=$category->getPointingToCategory()}
	{capture assign=url}{link_to action=edit id=$ptc}{/capture}
	<p>{t url=$url path=$ptc->getPath() escape=no}Toto je virtuální kategorie, která ve skutečnosti ukazuje na <a href="%1">/%2/</a>{/t}</p>

{else}

	{if !$parent || !$parent->isFilter()}
		{* Pokud je rodic filtr, nelze uz pridavat dalsi podkategorie *}
		<h3>{t}Podkategorie{/t}</h3>
		{assign var=children value=$category->getChildCategories()}
		{if $children}
			<ul>
				<ul class="list-group list-sortable" data-sortable-url="{link_to action="categories/set_rank"}">
					{foreach $children as $child}
						<li class="list-group-item" data-id="{$child->getId()}">
							{$child->getName()}
							<ul class="list-inline pull-right">
								<li>{a action="edit" id=$child}<i class="glyphicon glyphicon-edit"></i> {t}Upravit{/t}{/a}</li>
							</ul>
						</li>
					{/foreach}
				</ul>
			</ul>
		{else}
			<div class="img-message">
				{message}{t}Tato kategorie nemá podkategorie.{/t}{/message}
			</div>
		{/if}
		<p>{a action="create_new" parent_category_id=$category _class="btn btn-default" _id="imageToCard"}<i class="glyphicon glyphicon-plus-sign"></i> {t}Přidat novou podkategorii{/t}{/a}</p>
	{/if}


	<h3>{t}Produkty{/t}</h3>
	{if $category->isFilter()}
		<p>{t}Toto je filtr. Produkty zadávejte do jeho podkategorií.{/t}</p>
	{else}
		{assign var=cards value=$category->getCards()}
		{if !$cards}
			<p>
				Momentálně tady není žádný produkt.
			</p>
		{else}
			<ul class="list-group list-sortable" data-sortable-url="{link_to action="category_cards/set_rank" category_id=$category}">
				{foreach $cards as $card}
					<li class="list-group-item" data-id="{$card->getId()}">
						{render partial="shared/list_thumbnail" image=$card->getImage()}
						<a href="{link_to action="cards/edit" id=$card}" title="Editovat produkt">{$card->getName()}</a>
						{a_destroy action="category_cards/destroy" id=$card category_id=$category _title="Odebrat produkt" _class="confirm btn btn-danger btn-xs"}<i class="glyphicon glyphicon-remove"></i> <span class="hide">{t}Odebrat{/t}</span>{/a_destroy}
					</li>
				{/foreach}
			</ul>
		{/if}
		{if $category->allowProducts()}
			<p>{a action="category_cards/create_new" category_id=$category _class="btn btn-default"}<i class="glyphicon glyphicon-plus-sign"></i> {t}Přidat produkt{/t}{/a}</p>
		{else}
			{message}{t}Do této kategorie nelze přidávat produkty.{/t}{/message}
		{/if}
	{/if}

	<h3>{t}Doporučené produkty{/t}</h3>
	{if $category->isFilter()}
		<p>{t}Toto je filtr. Produkty zadávejte do jeho podkategorií.{/t}</p>
	{else}
		{assign var=cards value=$category->getRecommendedCards()}
		{if !$cards}
			<p>
				Momentálně tady není žádný produkt.
			</p>
		{else}
			<ul class="list-group list-sortable" data-sortable-url="{link_to action="category_recommended_cards/set_rank" category_id=$category}">
				{foreach $cards as $card}
					<li class="list-group-item" data-id="{$card->getId()}">
						{render partial="shared/list_thumbnail" image=$card->getImage()}
						<a href="{link_to action="cards/edit" id=$card}" title="Editovat produkt">{$card->getName()}</a>
						{a_destroy action="category_recommended_cards/destroy" id=$card category_id=$category _title="Odebrat produkt" _class="confirm btn btn-danger btn-xs"}<i class="glyphicon glyphicon-remove"></i> <span class="hide">{t}Odebrat{/t}</span>{/a_destroy}
					</li>
				{/foreach}
			</ul>
		{/if}
		{if $category->allowProducts()}
			<p>{a action="category_recommended_cards/create_new" category_id=$category _class="btn btn-default"}<i class="glyphicon glyphicon-plus-sign"></i> {t}Přidat doporučený produkt{/t}{/a}</p>
		{else}
			{message}{t}Do této kategorie nelze přidávat produkty.{/t}{/message}
		{/if}
	{/if}

{/if} {* $category->isAlias() *}
