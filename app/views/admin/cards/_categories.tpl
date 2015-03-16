<div id="categories">
	<h2>{t}Zařazení produktu do katalogového stromu{/t}</h2>


	<div id="categies">
		{render partial="category_items"}
	</div>

	{form_remote _data-type="json"}
		{render partial="shared/form_field" fields="category"}
		<button type="submit" class="btn btn-primary">{t}Zařadit do kategorie{/t}</button>
	{/form_remote}
</div>
