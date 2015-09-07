<ul class="checkSub clearfix">
    {foreach from=$categories item='category' name='categiesForeach'}
        {if isset($categories.level_depth) && $categories.level_depth == 1}
    <li class="root">
        {$category.name}
        {else}
        <li>
            <input type="checkbox" class="checkbox" name="categories[{$category.id_category}]" id="categories_{$category.id_category}" value="1" {if $category.active == 1}checked="checked" {/if}/> <label for="categories_{$category.id_category}">{l s=$category.name mod='videodesk'}</label>
        {/if}
        {if !empty($category.children) && sizeof($category.children) > 0}
        {include file=$categoriesPath categories=$category.children}
        {/if}
    </li>
    {/foreach}
</ul>