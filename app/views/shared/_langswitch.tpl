<div class="langswitch pull-right">
	<button class="langswitch__btn btn-skew--dir_left" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
		{$current_language.name} ↓
	</button>
	<ul class="langswitch__menu" aria-labelledby="dropdownMenu1">
		{foreach $supported_languages as $l}
		<li>
			<a href="{$l.sitch_url}">{$l.name}</a>
		</li>
		{/foreach}
	</ul>
</div>
