<h1>{$page_title}</h1>

<p class="lead">{$card->getTeaser()}</p>

{assign brand $card->getBrand()}
{if $brand}
	{t}Brand:{/t} {a action="brands/detail" id=$brand}{$brand->getName()}{/a}
{/if}


{foreach $card->getCardSections() as $section}
	<h3>{$section->getName()}</h3>

	{!$section->getBody()|markdown}

	{render partial="shared/photo_gallery" object=$section}

	{render partial="shared/attachments" object=$section}
{/foreach}
