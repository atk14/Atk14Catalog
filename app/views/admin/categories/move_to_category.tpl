<h1>{$page_title}</h1>

<dl>
	<dt>{t}Id{/t}</dt>
	<dd>{$category->getId()}</dd>
	<dt>{t}Path{/t}</dt>
	<dd>/{$category->getPath()}</dd>
</dl>

{render partial="shared/form"}
