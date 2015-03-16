<ol class="breadcrumb">
	<li>{a action="category_trees/index"}{t}Seznam stromů{/t}{/a}</li>
	{foreach $ancestors as $ancestor}
		<li>{a action="edit" id=$ancestor}{render partial="breadcrumb_name" category=$ancestor}{/a}</li>
	{/foreach}
	<li class="active">{render partial="breadcrumb_name"}</li>
</ol>


