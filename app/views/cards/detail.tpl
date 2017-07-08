{foreach $card->getCategories() as $category}
	<ol class="breadcrumb">
		<li>{a action="main/index"}{"ATK14_APPLICATION_NAME"|dump_constant}{/a}</li>
			{foreach $category->getPathOfCategories() as $c}
				<li>
					{a action="categories/detail" path=$c->getPath()}{$c->getName()}{/a}
				</li>
			{/foreach}
		<li class="active">{$card->getName()}</li>
	</ol>
{/foreach}

<h1>{$page_title}</h1>

<p class="lead">{$card->getTeaser()}</p>

{assign brand $card->getBrand()}
{if $brand}
	{t}Brand:{/t} {a action="brands/detail" id=$brand}{$brand->getName()}{/a}
{/if}

{render partial="shared/photo_gallery" object=$card}

{render partial="shared/attachments" object=$card}

{foreach $card->getCardSections() as $section}
	<h3>{$section->getName()}</h3>

	{!$section->getBody()|markdown}

	{render partial="shared/photo_gallery" object=$section}

	{render partial="shared/attachments" object=$section}

	{*** Variants ***}
	{if $section->getTypeCode()=="variants"}
		<ul>
			{render partial="product_item" from=$card->getProducts() item=product}
		</ul>
	{/if}

{/foreach}

{render partial="related_cards"}
{render partial="consumables"}
{render partial="accessories"}

{a action="information_requests/create_new" card_id=$card _class="btn btn-primary"}{t}Are you interested in this product?{/t}{/a}
