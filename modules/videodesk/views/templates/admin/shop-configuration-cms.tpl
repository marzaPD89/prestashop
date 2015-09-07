{foreach from=$pages item='page'}
<input type="checkbox" class="checkbox" name="cms[{$page.id_cms}]" id="cms_{$page.id_cms}" value="1" {if $page.active == 1}checked="checked" {/if}/> <label for="cms_{$page.id_cms}">{l s=$page.meta_title mod='videodesk'}</label>
{/foreach}