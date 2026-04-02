<div class="iobject iobject--video">
	<div class="{if USING_BOOTSTRAP5}ratio ratio-16x9{else}embed-responsive embed-responsive-16by9{/if}">
	{!$video->getHtml()}
	</div>
	{if $video->isTitleVisible() && ( $video->getTitle() || $video->getDescription() )}
	<div class="iobject__caption">
		{if $video->getTitle() && $video->isTitleVisible()}<div class="iobject__title">{$video->getTitle()}</div>{/if}
		{if $video->getDescription()}<div class="iobject__description">{$video->getDescription()}</div>{/if}
	</div>
	{/if}
</div>
