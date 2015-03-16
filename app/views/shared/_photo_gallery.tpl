{*
 * {render partial="shared/image_gallery" object=$brand}
 *}

{assign var=images value=Image::GetImages($object)}
{if $images}
	<section class="image-gallery">
		<h4>{t}Photo gallery{/t}</h4>
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
