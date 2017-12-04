<div class="dropdown pull-right">
	<button class="btn btn-default dropdown-toggle" type="button" id="langswitch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" title="{t}Change language{/t}">
		<img src="{$public}/images/languages/{$current_language.lang}.png" width="16" height="11" alt="">
		{$current_language.name}
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" aria-labelledby="langswitch">
		{foreach $supported_languages as $l}
			<li>
				<a href="{$l.switch_url}">
					<img src="{$public}/images/languages/{$l.lang}.png" width="16" height="11" alt="">
					{$l.name}
				</a>
			</li>
		{/foreach}
	</ul>
</div>
