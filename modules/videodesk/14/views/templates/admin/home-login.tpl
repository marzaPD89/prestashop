<script type="text/javascript">
	var bo_url = '{$bo_url}';

    {literal}
    $(document).ready(function() {

        $("#vd_sign_in_button").live("click", function() {
            $("#vd_sign_in_button").slideUp();
            $("#vd_sign_in_form").slideDown();
        }); 
        
        $('span.infobulle').cluetip({splitTitle: '|'});
        
        $('#bo_redirect').click(function() {
        	var win = window.open(bo_url, '_blank', 'window settings');
			win.focus();
			return false;
		});

    });
    {/literal}
</script>

<div id="vd_content">

<!-- < Edito -->
<div id="vd_edito">
    <iframe src="{$edito.src}" width="{$edito.width}px" height="{$edito.height}px" frameBorder="0"></iframe>
</div>
<!-- > Edito -->

{if $errors|@count > 0}
    <div class="error">
        <ul>
            {foreach from=$errors item=error}
                <li>{$error}</li>
            {/foreach}
        </ul>
    </div>
{/if}

<div class="login clearfix">
    <!-- < Sign In -->
    <div id="vd_sign_in" class="block left clearfix">
        <h2>{l s='Already a member? Sign in!' mod='videodesk'}</h2>
                
        {if $errors|@count == 0}
            <span id="vd_sign_in_button" class="blueButton">{l s='Sign In' mod='videodesk'}</span>
        {/if}
        
        <div id="vd_sign_in_form" {if $errors|@count == 0}style="display:none;"{/if}>
            <form action="{$request_uri}" method="post">
                {foreach from=$shops item=shop_group}
                    {if $isMultiShop}
                        <p class="title">{l s='Group:'} {$shop_group.name}</p>
                    {/if}
                    {foreach from=$shop_group.shops item=shop}
                        {assign var="index" value="`$shop.id_shop_group`_`$shop.id_shop`"}
                        <label>{if $isMultiShop}{$shop.name} {else}{l s="ID for your shop:" mod="videodesk"}{/if}</label>
                        <input type="text" name="website_id[{$shop.id_shop_group}][{$shop.id_shop}]" placeholder="{l s='Website ID' mod='videodesk'}" value="{if isset($website_id[$shop.id_shop_group][$shop.id_shop])}{$website_id[$shop.id_shop_group][$shop.id_shop]}{/if}" style="width: 150px;"/>
                        <span class="infobulle" title="{l s='Home Help title' mod='videodesk'}|{l s='Home Help content' mod='videodesk'}"> ? </span>
                    {/foreach}
                {/foreach}
                <input type="submit" name="submitSignIn" value="{l s='Sign In' mod='videodesk'}" class="blueButton" style="float:left;" />
            </form>
        </div>
    </div>
    <!-- > Sign In -->
    
    {if $account_exists}
    <!-- < Sign Up -->
    <div id="vd_sign_up_home" class="block right clearfix">
        <h2>{l s='Visit your Videodesk Back Office' mod='videodesk'}</h2>
        
        <span id="bo_redirect" class="blueButton">{l s='Go' mod='videodesk'}</span>
    </div>
    <!-- > Sign Up -->
    {else}
    <!-- < Sign Up -->
    <div id="vd_sign_up_home" class="block right clearfix">
        <h2>{l s='New to videodesk? Sign up!' mod='videodesk'}</h2>
        
        <form action="{$request_uri}" method="post">
            <input type="submit" name="submitSignUp" value="{l s='Sign Up' mod='videodesk'}" class="blueButton" />
        </form>
        
        <p class="spotted"><img src="{$img_dir}spot.png" /> {l s='Free setup and Free to use' mod='videodesk'}
        	<a href="{$pricing_url}" title="{l s='Read more' mod='videodesk'}" target="_blank" class="vd">{l s="Read more" mod="videodesk"}</a>
        </p>
    </div>
    <!-- > Sign Up -->
    {/if}
    
    <br class="clearBoth" />
</div>

</div>

{include file=$module_help lang_iso=$lang_iso}