<h1>{$page_title}</h1>

{render partial="shared/form"}

<hr>

{render partial="shared/image_gallery" object=$collection}

<hr>

<h3>{t}Products in the collection{/t}</h3>
{render partial="collection_cards"}
