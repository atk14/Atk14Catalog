<h4>{a action="cards/detail" id=$card}{$card->getName()}{/a}</h4>
{if $card->getImage()}
	{a action="cards/detail" id=$card}{!$card->getImage()|pupiq_img:"200x150xcrop"}{/a}
{/if}
<p>{$card->getTeaser()}</p>
