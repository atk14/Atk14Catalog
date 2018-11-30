<h1>{$page_title}</h1>

{if !$stores}

	<p>{t}Currently we have no store.{/t}</p>

{else}

	<div class="card-deck card-deck--sized">
		{render partial="store_item" from=$stores item=store}
	</div>

{/if}
