{*
 * Tato sablonka se vyrenderuje do jsonu pri XHR uploadu obrazku do adminu (viz controllers/admin/images_controller.php)
 *}
<li class="list-group-item clearfix" data-id="{$image->getId()}">

	<img {!$image|img_attrs:"!80x80"} class="pull-left" style="margin-right: 0.5em;">

	{dropdown_menu clearfix=false}
		{a action="images/edit" id=$image}{icon glyph=edit} {t}Edit{/t}{/a}
		{a_destroy action="images/destroy" id=$image}{icon glyph=remove} {t}Remove image{/t}{/a_destroy}
	{/dropdown_menu}

	<div>
		{if $image->getName()}
			<h4 class="media-heading">{$image->getName()}</h4>
		{/if}

		{if $image->getDescription()}
			<p>{$image->getDescription()}</p>
		{/if}
	</div>

</li>
