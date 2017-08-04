<h1>{$page_title}</h1>

{form}
	{foreach $sections as $section}
		<fieldset>
			<legend>{$section.legend}</legend>
			{render partial="shared/form_field" fields=$section.fields}
		</fieldset>
	{/foreach}

	{render partial="shared/form_button"}
{/form}
