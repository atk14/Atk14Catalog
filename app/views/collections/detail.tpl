<header>
	<div class="jumbotron bg-transparent border border-secondary">
		<div class="row">
			<div class="col-12 col-md-6 d-md-flex flex-column justify-content-center">
				{admin_menu for=$collection}
				<h1>{$page_title} </h1>
				{if $collection->getTeaser()}
					<div class="lead">{!$collection->getTeaser()|markdown}</div>
				{/if}
			</div>
			<div class="col-12 col-md-6 text-md-right">
				{!$collection->getImageUrl()|pupiq_img:"300x300":"class='img-fluid'"}
			</div>
		</div>
	</div>
</header>

<section class="border-top-0">
	{!$collection->getDescription()|markdown}
</section>

{render partial="shared/photo_gallery" object=$collection}

{capture assign=title}{t collection=$collection->getName()}Products in the collection %1{/t}{/capture}
{render partial="shared/card_list" cards=$collection->getCards() title=$title}
