<h1>{$store->getName()}</h1>


{if $store->getTeaser()}
	<p class="lead">{$store->getTeaser()}</p>
{/if}

{!$store->getImageUrl()|pupiq_img:"300x300"}

{if $store->getOpeningHours()}
	<p class="lead">
		<strong>{t}Opening hours{/t}:</strong><br>
		{!$store->getOpeningHours()|h|nl2br}
	</p>
{/if}

{if $store->getAddress()}
	<p class="lead">
		<strong>{t}Address{/t}:</strong><br>
		{!$store->getAddress()|h|nl2br}
	</p>
{/if}

{if $store->getPhone() || $store->getEmail()}
	<p class="lead">
		{if $store->getPhone()}
			{icon glyph=phone} <a href="tel:{$store->getPhone()}">{$store->getPhone()}</a><br>
		{/if}
		{if $store->getEmail()}
			{icon glyph="envelope"} <a href="mailto:{$store->getEmail()}">{$store->getEmail()}</a>
		{/if}
	</p>
{/if}

{!$store->getDescription()|markdown}

{render partial="shared/photo_gallery" object=$store photo_gallery_title=""}
