<h1>{$page->getTitle()}</h1>

<div>
{!$page->getTeaser()|markdown}
</div>

<div>
{!$page->getBody()|markdown}
</div>

{if $child_pages}
	<hr>
	<h4>{t}Subpages{/t}</h4>
	<ul>
	{foreach $child_pages as $child_page}
		<li>{a action=detail id=$child_page}{$child_page->getTitle()}{/a}</li>
	{/foreach}
	</ul>
{/if}
