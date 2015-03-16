{
	"snippet": {jstring}{render partial="category_items"}{/jstring},
	"hasErrors": {if !$form->has_errors()}false{else}true{/if},
	"errors": {$form->get_errors()|@json_encode nofilter}
}
