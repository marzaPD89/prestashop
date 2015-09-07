<script type="text/javascript">
    var baseDir = "{$module_dir}";
</script>
<br />

<div id="videodesk_calls" {if $type == 'customer'}style="width: 50%;"{/if}>  
	<input type="hidden" name="vd_token" id="vd_token" value="{$token}" />
        {if $type == 'order'}
        	<fieldset>
            <legend><img src="{$module_dir}logo.gif">{l s='Videodesk calls' mod='videodesk'}</legend>
        {else}
        	{if $type == 'customer'}
        		<h2>{l s='Videodesk calls' mod='videodesk'} ({$calls|count})</h2>
        	{/if}
		{/if}  
        <table width="100%" cellspacing="0" cellpadding="0" id="videodesk_calls_table" class="table">
        <colgroup>
                <col width="25%">
                <col width="15%">
                {if $type == 'customer'}<col width="10%">{/if}
                <col width="20%">
                <col width="20%">
                <col width="10%">
        </colgroup>
                <thead>
                <tr>
                        <th>{l s='Date' mod='videodesk'}</th>
                        <th>{l s='Type' mod='videodesk'}</th>
                        {if $type == 'customer'}<th>{l s='Cart' mod='videodesk'}</th>{/if}
                        <th>{l s='Employee' mod='videodesk'}</th>
                        {if $isMultishop}<th>{l s='Shop' mod='videodesk'}</th>{/if}
                        <th>{l s='Page' mod='videodesk'}</th>
                        <th>{l s='Details' mod='videodesk'}</th>
                </tr>
                </thead>
                <tbody>
                    {foreach from=$calls key=k item=call}
                        <tr id="call{$call.call->id}">
                            <td>{$call.call->call_date}</td>
                            <td>{$call.call->call_type}</td>
                            {if $type == 'customer'}<td>{if isset($call.call->id_cart)}<a href="index.php?tab=AdminCarts&id_cart={$call.call->id_cart}&viewcart&token={getAdminToken tab='AdminCarts'}"><img src="../img/admin/details.gif" /></a>{/if}</td>{/if}
                            <td>{$call.employee->firstname} {$call.employee->lastname}</td>
                            {if $isMultishop}<td>{$call.shop_name}</td>{/if}
                            <td>{$call.call->connexion_page}</td>
                            <td><span style="text-decoration:underline; cursor:pointer;" class="detail" id="{$call.call->id}" ><img src="{$img_dir}admin/details.gif" alt="{l s='See Detail' mod='videodesk'}"></span></td>
                        </tr>
                    {/foreach}
                </tbody>
        </table>
	{if $type == 'order'}
    </fieldset>
    {/if}

    <div id="call_bg" style="display:none;"></div>

    <div id="call_detail" style="display:none;">
        <p id="close_call">close<img style="padding-left:5px;" src="{$module_dir}views/img/delete.gif"></p>
        <div id="call_content">
            <img class="ajaxloader" src="{$module_dir}views/img/ajax-loader.gif">
        </div>
    </div>           
        
</div>