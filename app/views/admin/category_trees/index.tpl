<h1>{$page_title}</h1>

<p>{a action=create_new _class="btn btn-primary"}<i class="glyphicon glyphicon-plus-sign"></i> {t}Vytvořit nový strom{/t}{/a}</p>

<ul class="list-group list-sortable" data-sortable-url="{link_to action="set_rank"}">
	{foreach $roots as $root}
		<li class="list-group-item" data-id="{$root->getId()}">
			{a action="detail" id=$root}{$root->getName()}{/a}
			{if $root->isDeletable()}
				<div class="pull-right">
					{capture assign="confirm"}{t 1=$root->getName()|h escape=no}Chystáte se smazat katalogvý strom %1
Jste si jistý/á?{/t}{/capture}
					{a_remote action=destroy id=$root _method=post _confirm=$confirm _title="{t}Smazat strom{/t}" _class="btn btn-danger btn-xs"}<span class="glyphicon glyphicon-remove"></span>{/a_remote}
				</div>
			{/if}
		</li>
	{/foreach}
</ul>
