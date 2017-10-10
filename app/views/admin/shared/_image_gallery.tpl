{*
 * {render partial="shared/image_gallery" object=$brand}
 *}

<h2>{t}Photo gallery{/t}</h2>

{assign var=images value=Image::GetImages($object)}

{if !$images}
	<div class="img-message">
		<p>{t}Currently there are no images{/t}</p>
	</div>
{/if}

<ul class="list-group list-group-images list-sortable" data-sortable-url="{link_to action="images/set_rank"}">
	{if $images}
		{render partial="shared/image_gallery_item" from=$images item=image}
	{/if}
</ul>

<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
    <span class="sr-only">60%</span>
  </div>
</div>

<p>{a action="images/create_new" table_name=$object->getTableName() record_id=$object->getId() _class="btn btn-default" _id="imageToGallery"}<i class="glyphicon glyphicon-plus-sign"></i> {t}Add an image{/t}{/a}</p>
