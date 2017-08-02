{*
 * Vykresli obrazek vhodny do nejakeho seznamu.
 *
 * {render partial="shared/list_thumbnail" image=$card->getImage()}
 *}
{if $image}
	<a href="{$image|img_url:800x800}" title="{t}Show larger image{/t}">{!$image|pupiq_img:"!80x80"}</a>
{else}
	<img src="{$public}images/camera.svg" width="80" height="80" title="{t}no image{/t}">
{/if}
