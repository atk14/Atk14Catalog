{assign parent_category $category->getParentCategory()}

{a namespace="admin" action="categories/edit" id=$category}{!"edit"|icon} {t}Edit category{/t}{/a}
{a namespace="admin" action="category_cards/create_new" category_id=$category}{!"plus"|icon} {t}Add a product to this category{/t}{/a}
{if $parent_category}
	{a namespace="admin" action="categories/create_new" parent_category_id=$parent_category}{!"plus"|icon} {t}Add new category (sibling){/t}{/a}
{/if}
{a namespace="admin" action="categories/create_new" parent_category_id=$category}{!"plus"|icon} {t}Add new subcategory (child){/t}{/a}
{a namespace="admin" action="category_trees/detail" id=$category->getRootCategory()}{!"tree"|icon} {t}Show category tree{/t}{/a}
