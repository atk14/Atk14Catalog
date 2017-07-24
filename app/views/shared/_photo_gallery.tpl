{*
 * {render partial="shared/photo_gallery" object=$brand}
 *}

{assign var=images value=Image::GetImages($object)}

{if $images}
	{if !isset($photo_gallery_title)}{capture assign="photo_gallery_title"}{t}Photo gallery{/t}{/capture}{/if}
	<section class="image-gallery">
		{if $photo_gallery_title}<h4>{$photo_gallery_title}</h4>{/if}
		<ul class="row list-unstyled">
			{foreach $images as $image}
				<li class="col-xs-2">
					<a href="{$image|img_url:"1024"}" title="{if $image->getDescription()}{$image->getDescription()}{/if}">
						<img {!$image|img_attrs:"!120x120"} alt="{$image->getName()}" class="img-thumbnail">
					</a>
				</li>
			{/foreach}
		</ul>
	</section>
{/if}
