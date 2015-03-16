{*
 * Tato sablonka se vyrenderuje do jsonu pri XHR uploadu obrazku do adminu (viz controllers/admin/images_controller.php)
 *}
<li class="list-group-item media clearfix" data-id="{$image->getId()}">
	<div class="btn-group pull-right">
		{capture assign="title"}{t}Odpojit obrázek.{/t}{/capture}
		{a_destroy action="images/destroy" id=$image _title=$title _class="confirm btn btn-danger btn-xs pull-right"}<span class="glyphicon glyphicon-remove"></span>{/a_destroy}
	</div>

	<a href="{link_to action="images/edit" id=$image}" title="{t}Editovat tento obrázek{/t}" class="pull-left">{!$image|pupiq_img:"!80x80"}</a>

	<div class="media-body">
		{if $image->getName()}
			<h4 class="media-heading">{$image->getName()}</h4>
		{/if}

		{if $image->getDescription()}
			<p>{$image->getDescription()}</p>
		{/if}
	</div>
</li>
