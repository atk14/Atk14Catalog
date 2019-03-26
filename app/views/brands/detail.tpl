<header>
	<div class="jumbotron bg-transparent border border-secondary">
		<div class="row">
			<div class="col-12 col-md-6 d-md-flex flex-column justify-content-center">
				{admin_menu for=$brand}
				<h1>{$page_title}</h1>
				{if $brand->getTeaser()}
					<div class="lead">{!$brand->getTeaser()|markdown}</div>
				{/if}
			</div>
			<div class="col-12 col-md-6 text-md-right">
				{!$brand->getLogoUrl()|pupiq_img:"300x300":"class='img-fluid'"}
			</div>
		</div>
	</div>
</header>

<section class="border-top-0">
	{!$brand->getDescription()|markdown}
</section>

{render partial="shared/photo_gallery" object=$brand}

{render partial="shared/card_list" cards=$brand->getCards()}
