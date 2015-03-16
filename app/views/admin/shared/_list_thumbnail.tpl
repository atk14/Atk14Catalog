{*
 * Vykresli obrazek vhodny do nejakeho seznamu.
 *
 * {render partial="shared/list_thumbnail" image=$card->getImage()}
 *}
{if $image}
	<a href="{$image|img_url:800x800}" title="{t}ukázat obrázek ve větší velikosti{/t}">{!$image|pupiq_img:"!80x80"}</a>
{else}
	<span class="glyphicon glyphicon-eye-close text-muted" title="{t}Fotka chybí{/t}"></span>
{/if}
