<h1>
	{$store->getName()}
	{if $store->getTeaser()}<br><small>{$store->getTeaser()}</small>{/if}
</h1>

<div class="lead">

	{if $store->getOpeningHours()}
		<p>
			<strong>{t}Opening hours{/t}:</strong><br>
			{!$store->getOpeningHours()|h|nl2br}
		</p>
	{/if}

	{if $store->getAddress()}
		<p>
			<strong>{t}Address{/t}:</strong><br>
			{!$store->getAddress()|h|nl2br}
		</p>
	{/if}

	{if $store->getPhone() || $store->getEmail()}
		<p>
			{if $store->getPhone()}
				{icon glyph=phone} <a href="tel:{$store->getPhone()}">{$store->getPhone()}</a><br>
			{/if}
			{if $store->getEmail()}
				{icon glyph="envelope"} <a href="mailto:{$store->getEmail()}">{$store->getEmail()}</a>
			{/if}
		</p>
	{/if}

</div>

{!$store->getDescription()|markdown}

{render partial="shared/photo_gallery" object=$store photo_gallery_title=""}
