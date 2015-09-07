{foreach from=$pages item='page'}
<input type="checkbox" class="checkbox" name="modules[{$page.id_module}]" id="modules_{$page.id_module}" value="1" {if $page.active == 1}checked="checked" {/if}/> <label for="modules_{$page.id_module}">{l s=$page.name|capitalize mod='videodesk'}</label>
{/foreach}