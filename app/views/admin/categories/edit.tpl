<h1>
	{$page_title}

	{dropdown_menu clearfix=false}
		{if $category->allowSubcategories()}
			{a action="create_new" parent_category_id=$category}{icon glyph="plus-circle"} {t}Add a new subcategory{/t}{/a}
		{/if}
		{if $category->isVisible() && !$category->isFilter() && !$category->isSubcategoryOfFilter()}
			{a namespace="" action="categories/detail" path=$category->getPath()}{icon glyph="eye-open"} {t}Show on web{/t}{/a}
		{/if}
		{if $category->canBeMoved()}
			{a action=move_to_category id=$category}{icon glyph="arrows-alt"} {t}Move the category{/t}{/a}
		{/if}
		{if $category->canBeAliased()}
			{a action=create_alias id=$category}{icon glyph="share-alt"} {t}Create an alias{/t}{/a}
		{/if}
		{if $category->isDeletable()}
			{capture assign=confirmation}{t name=$category->getName() escape=no}You are about to delete the category %1!
Also all subcategories will be deleted. Deletion cannot be undone.

Do you really want this?{/t}{/capture}
			{a_destroy id=$category _confirm=$confirmation}{icon glyph="trash-alt"} {t}Delete{/t}{/a_destroy}
		{/if}
	{/dropdown_menu}
</h1>


{assign var=parent value=$category->getParentCategory()}

<table class="table">
	<tbody>
	<tr>
		<th>#</th><td>{$category->getId()}</td>
	</tr>
	<tr>	
		<th>{t}Path{/t}</th><td>/{$category->getPath()}/</td>
	</tr>

	{if $category->isAlias()}

		{assign pointing_to_category $category->getPointingToCategory()}

		<tr>
			<th>{t}Pointing to category{/t}</th>
			<td>{a action="edit" id=$pointing_to_category}/{$pointing_to_category->getPath()}/{/a}</td>
		</tr>

	{else}

		<tr>
			<th>{t}Is a filter?{/t}</th>
			<td>{$category->isFilter()|display_bool}</td>
		</tr>

		<tr>
			<th>{t}Can products be inserted here?{/t}</th>
			<td>{$category->allowProducts()|display_bool}</td>
		</tr>

	{/if}

	</tbody>
</table>

{render partial="shared/form"}

{if $category->isAlias()}
	<hr>

	{assign var=ptc value=$category->getPointingToCategory()}
	{capture assign=url}{link_to action=edit id=$ptc}{/capture}
	<p>{t url=$url path=$ptc->getPath() escape=no}This is a virtual category, which is actually pointing to <a href="%1">/%2/</a>{/t}</p>

{else}

	{if !$category->isSubcategoryOfFilter()}
		{* Pokud je rodic filtr, nelze uz pridavat dalsi podkategorie *}
		<hr>

		<h3 id="subcategories">
			{if $category->allowSubcategories()}
				{button_create_new parent_category_id=$category return_to_anchor="subcategories"}{t}Add a new subcategory{/t}{/button_create_new}
			{/if}
			{t}Subcategories{/t}
		</h3>
		{assign var=children value=$category->getChildCategories()}
		{if $children}
				<ul class="list-group list-sortable" data-sortable-url="{link_to action="categories/set_rank"}">
					{foreach $children as $child}
						<li class="list-group-item" data-id="{$child->getId()}">
							{if $child->isFilter()}<em>{t}filter{/t}:</em>{/if}
							{if $child->isAlias()}<em>{t}alias{/t}:</em>{/if}
							{$child->getName()}
							{if !$child->isVisible()}<em>({t}invisible{/t})</em>{/if}
							{dropdown_menu}
								{a action="edit" id=$child}{icon glyph="edit"} {t}Edit{/t}{/a}
								{if $child->isDeletable()}
										{a_destroy action="categories/destroy" id=$child}{!"remove"|icon} {t}Delete{/t}{/a_destroy}
								{/if}
							{/dropdown_menu}
						</li>
					{/foreach}
				</ul>
		{else}

			<div class="img-message">
				<p>{t}This category has no subcategories.{/t}</p>
			</div>

		{/if}
	{/if}

	{if !$category->isFilter()}
		<hr>

		<h3 id="products">
			{if $category->allowProducts()}
				{button_create_new action="category_cards/create_new" category_id=$category return_to_anchor="products"}{t}Add a product{/t}{/button_create_new}
			{/if}
			{t}Products{/t}
		</h3>

		{if $too_many_cards_in_category}

			<p>{t count=$cards_in_category}There are too many products in the category (%1){/t} &rarr; {a action="category_cards/index" category_id=$category}{t}show product list{/t}{/a}</p>

		{else}

			{assign var=cards value=$category->getCards()}
			{if !$cards}
				<p>
					{t}There is currently no product here.{/t}
				</p>
			{else}
				<ul class="list-group list-sortable" data-sortable-url="{link_to action="category_cards/set_rank" category_id=$category}">
					{foreach $cards as $card}
						<li class="list-group-item" data-id="{$card->getId()}">
							{render partial="shared/list_thumbnail" image=$card->getImage()}
							{$card->getName()}
							{dropdown_menu}
								{a action="cards/edit" id=$card}{!"edit"|icon} {t}Edit product{/t}{/a}
								{a_destroy action="category_cards/destroy" id=$card category_id=$category}{!"remove"|icon} {t}Remove product{/t}{/a_destroy}
							{/dropdown_menu}
						</li>
					{/foreach}
				</ul>
			{/if}
			{if !$category->allowProducts()}
				<p>{t}Into this category products cannot be added.{/t}</p>
			{/if}

		{/if}
	{/if}

	{if !$category->isFilter() && !$category->isSubcategoryOfFilter()}
		<hr>

		<h3 id="recommended_products">
			{if $category->allowProducts()}
				{button_create_new action="category_recommended_cards/create_new" category_id=$category return_to_anchor="recommended_products"}{t}Add a recommended product{/t}{/button_create_new}
			{/if}
			{t}Recommended products{/t}
		</h3>

		{assign var=cards value=$category->getRecommendedCards()}
		{if !$cards}
			<p>
				{t}There is currently no product here.{/t}
			</p>
		{else}
			<ul class="list-group list-sortable" data-sortable-url="{link_to action="category_recommended_cards/set_rank" category_id=$category}">
				{foreach $cards as $card}
					<li class="list-group-item" data-id="{$card->getId()}">
						{render partial="shared/list_thumbnail" image=$card->getImage()}
						{$card->getName()}
						{dropdown_menu}
							{a action="cards/edit" id=$card}{!"edit"|icon} {t}Edit product{/t}{/a}
							{a_destroy action="category_recommended_cards/destroy" id=$card category_id=$category}{!"remove"|icon} {t}Remove product{/t}{/a_destroy}
						{/dropdown_menu}
					</li>
				{/foreach}
			</ul>
		{/if}
		{if !$category->allowProducts()}
			<p>{t}Into this category products cannot be added.{/t}</p>
		{/if}
	{/if}

{/if} {* $category->isAlias() *}
