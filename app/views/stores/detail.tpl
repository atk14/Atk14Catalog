<header>
	<div class="jumbotron bg-transparent border border-secondary">
		<div class="row">
			<div class="col-12 col-md-6 d-md-flex flex-column justify-content-center">
				{admin_menu for=$store}
				<h1>{$store->getName()}</h1>
				{if $store->getTeaser()}
					<div class="lead">{!$store->getTeaser()|markdown}</div>
				{/if}
			</div>
			<div class="col-12 col-md-6 text-md-right">
				{!$store->getImageUrl()|pupiq_img:"300x300":"class='img-fluid'"}
			</div>
		</div>
	</div>
</header>

<section class="store-properties">
	<div class="row">
		{if $store->getOpeningHours()}
			<p class="col-12 col-md store-properties-column">
				<strong>{!"clock"|icon:"solid"} {t}Opening hours{/t}:</strong><br>
				{!$store->getOpeningHours()|h|nl2br}
			</p>
		{/if}

		{if $store->getAddress()}
			<p class="col-12 col-md store-properties-column">
				<strong>{!"map-marker-alt"|icon} {t}Address{/t}:</strong><br>
				{!$store->getAddress()|h|nl2br}
			</p>
		{/if}

		{if $store->getPhone() || $store->getEmail()}
			<p class="col-12 col-md store-properties-column">
				{if $store->getPhone()}
					{icon glyph=phone} <a href="tel:{$store->getPhone()}">{$store->getPhone()}</a><br>
				{/if}
				{if $store->getEmail()}
					{icon glyph="envelope"} <a href="mailto:{$store->getEmail()}">{$store->getEmail()}</a>
				{/if}
			</p>
	{/if}
	</div>
</section>

<section class="store-description">
	{!$store->getDescription()|markdown}
</section>

{render partial="shared/photo_gallery" object=$store photo_gallery_title=""}
