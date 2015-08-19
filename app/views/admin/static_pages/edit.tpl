<h1>{$page_title}</h1>

{render partial="shared/form"}

<hr>

<h2>{t}Subpages{/t}</h2>
{if $child_static_pages}
	<ul class="list-group list-sortable" data-sortable-url="{link_to action="static_pages/set_rank"}">
	{foreach $child_static_pages as $sp}
		<li class="list-group-item media clearfix" data-id="{$sp->getId()}">
			<div class="btn-group pull-right">
				{a action=edit id=$sp}<span class="glyphicon glyphicon-edit"></span> {t}Edit{/t}{/a}
			</div>
			{$sp->getTitle()}
		</li>
	{/foreach}
	</ul>
{else}
	<p>{t}This page has no subpages{/t}</p>	
{/if}

<p>{a action="static_pages/create_new"  parent_static_page_id=$static_page _class="btn btn-default"}<i class="glyphicon glyphicon-plus-sign"></i> {t}Create new subpage{/t}{/a}</p>

