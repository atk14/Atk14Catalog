<h1>{$page_title}</h1>

{render partial="shared/form"}

<hr>

{render partial="shared/image_gallery" object=$collection}

<hr>

<h2>{t}Products in the collection{/t}</h2>
{render partial="collection_cards"}
