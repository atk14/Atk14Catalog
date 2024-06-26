{dropdown_menu clearfix=false}
	{a namespace="" action="stores/detail" id=$store}{!"eye-open"|icon} {t}Visit public link{/t}{/a}
{/dropdown_menu}

<h1>{$page_title}</h1>

{render partial="shared/form"}

<hr>

{render partial="shared/image_gallery" object=$store}
