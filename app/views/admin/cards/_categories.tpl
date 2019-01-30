<div id="categories">
	<h3>{t}Product placement in the catalog tree{/t}</h3>


	<div id="categies">
		{render partial="category_items"}
	</div>

	{form_remote _data-type="json"}
		{render partial="shared/form_field" fields="category"}
		<button type="submit" class="btn btn-primary">{t}Add product to a category{/t}</button>
	{/form_remote}
</div>
