<ol class="breadcrumb">
	<li>{a action="main/index"}{t}Homepage{/t}{/a}</li>
		{foreach $parent_categories as $pc}
			<li>{a path=$pc.path}{$pc.name}{/a}</li>
		{/foreach}
	<li class="active">{$category->getName()}</li>
</ol>

<h1>{$page_title} ({$cards_finder->getRecordsCount()})</h1>

<p class="lead">{$category->getTeaser()}</p>

{!$category->getImageUrl()|pupiq_img:"300x300"}
{!$category->getDescription()|markdown}

{if $child_categories}
	<section class="child-categories">
		<h4>{t}Subcategories{/t}</h4>
		<nav>
			<ul class="list-group">
				{foreach $child_categories as $cc}
					<li class="list-group-item">
						{if $cc.category->getImage()}
							{a path=$cc.path}{!$cc.category->getImage()|pupiq_img:"36x36"}{/a}
						{/if}
						<h4 class="list-group-item-heading">{a path=$cc.path}{$cc.name}{/a} ({$cc.cards_count})</h4>
						{if $cc.category->getTeaser()}
							<p class="list-group-item-text">{$cc.category->getTeaser()}</p>
						{/if}
					</li>
				{/foreach}
			</ul>
		</nav>
	</section>
{/if}

<section class="products">
	<h4>{t}Products{/t}</h4>
	{if $cards_finder->isEmpty()}
		<p>{t}No product has been found.{/t}</p>
	{else}
		<ul class="list product-list" data-record_count="{$cards_finder->getRecordsCount()}">

			{foreach $cards_finder->getRecords() as $card}
				<li class="list-item">{render partial="shared/card_item" card=$card}</li>
			{/foreach}
			
		</ul>
		{paginator finder=$cards_finder}
	{/if}
</section>

{if $canonical_path}
	{content for=head}
		<link rel="canonical" href="{link_to action=detail path=$canonical_path}" />
	{/content}
{/if}
