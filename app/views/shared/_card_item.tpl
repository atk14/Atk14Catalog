<h4>{a action="cards/detail" id=$card}{$card->getName()}{/a}</h4>
{a action="cards/detail" id=$card}{trim}
	{if $card->getImage()}
		{!$card->getImage()|pupiq_img:"200x150xcrop"}
	{else}
		<img src="{$public}images/camera.svg" width="200" height="150" title="{t}no image{/t}">
	{/if}
{/trim}{/a}
<p>{$card->getTeaser()}</p>
