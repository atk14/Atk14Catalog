{if $form->has_errors()}

	$form.replaceWith({jstring}{render partial="add_technical_specification_form"}{/jstring});
	
{else}

	$("#technical_specifications").replaceWith({jstring}{render partial="technical_specifications" add_technical_specification_form=$form}{/jstring});

	ADMIN.utils.handleSortables();
	ADMIN.utils.handleSuggestions();

	$( "#id_technical_specification_key_id" ).focus();

{/if}
