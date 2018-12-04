<header>
	<h1>{$page_title}</h1>
</header>

<section>
	{form _class="form-horizontal"}
		{render partial="shared/form_error"}
		<fieldset>
			{render partial="shared/form_field" fields=$form->get_field_keys()}
			{render partial="shared/form_button"}
		</fieldset>
	{/form}
</section>