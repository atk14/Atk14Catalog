{a action="cards/detail" id=$card _class="card"}{trim}
	{if $card->getImage()}
		{!$card->getImage()|pupiq_img:"400x300xcrop":"class='card-img-top'"}
	{else}
		<img src="{$public}images/camera.svg" width="400" height="300" title="{t}no image{/t}">
	{/if}
	<div class="card-body">
		<h4 class="card-title">{$card->getName()}</h4>
		<div class="card-text">{$card->getTeaser()}</div>
	</div>
{/trim}{/a}
