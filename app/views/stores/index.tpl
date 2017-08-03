<h1>{$page_title}</h1>

{if !$stores}

	<p>{t}Currently we have no store.{/t}</p>

{else}

	<ul>
		{render partial="store_item" from=$stores item=store}
	</ul>

{/if}
