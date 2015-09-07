<script type="text/javascript">
    {literal}
    $(document).ready(function() {
        $('.checkSub input[type=checkbox]').on('change', function() {
            if ($(this).attr('checked') == 'checked') {
                $(this).parent().find('input[type=checkbox]').attr('checked', 'checked');
            }
        });
        $('.check').on('change', function() {
            matches = $(this).attr('id').match('(check([0-9]*))');
            if ($('#'+matches[1]).attr('checked') == 'checked') {
                $('#'+matches[1]).parent().parent().find('input[type=checkbox]').attr('checked', 'checked');
                $('#label_'+matches[1]).html('{/literal}{l s='Uncheck All' mod='videodesk'}'{literal}).text();
            }
            else {
                $('#'+matches[1]).parent().parent().find('input[type=checkbox]').removeAttr('checked');
                $('#label_'+matches[1]).html('{/literal}{l s='Check All' mod='videodesk'}{literal}').text();
            }
        });
        
        $('span.infobulle').cluetip({splitTitle: '|'});
    });
    {/literal}
</script>

<div id="vd_content">

    <!-- < Edito -->
    <div id="vd_edito">
        <iframe src="{$edito.src}" width="{$edito.width}px" height="{$edito.height}px" frameBorder="0"></iframe>
    </div>
    <!-- > Edito -->
    
    <div id="errors" class="error" {if $errors|@count > 0}style="display:block"{/if}>
    {if $errors|@count > 0}
        <ul>
            {foreach from=$errors item=error}
                <li>{$error}</li>
            {/foreach}
        </ul>
    {/if}
    </div>
    
    {if !empty($shops)}
    <div class="configuration shopConf">
        <form action="{$return_url}&submitShopConfiguration" method="post">
            <input type="hidden" name="id_shop" value="{$id_shop}" />
            <input type="hidden" name="old_shop_context" value="{$old_shop_context}" />
            
            {foreach from=$shops item='shops_group'}
                {if sizeof($shops) > 1}
            <div class="shops_group">
                <h3>{$shops_group.name}</h3>
                {/if}
                {foreach from=$shops_group.shops item='shop'}
                <div class="shop">
                	{if sizeof($shops_group.shops) > 1}
                    	<h4 class="blueTab">{$shop.name}</h4>
                    {/if}
                    <div class="bg_gray content clearfix">
                        <div class="block basic_configuration clearfix" style="margin-top:0;">
                        	<p class="title">{l s='Videodesk Common Display' mod='videodesk'} <span class="infobulle_conf"><span class="infobulle" title="{l s='Help Display Configuration title' mod='videodesk'}|{l s='Help Display Configuration content' mod='videodesk'}"> ? </span></span></p>
                        	<p>
                        		<label for="displayed_0" class="conf_display"><img src="{$img_dir}not-displayed.png" alt="{l s='Hide' mod='videodesk'}" title="{l s='Hide' mod='videodesk'}" /></label><input type="radio" class="radio conf_display" name="displayed" id="displayed_0" value="0" {if $shop.configuration.displayed == 0}checked="checked" {/if}/> <label for="displayed_0" class="conf_display_text">{l s='Hide' mod='videodesk'}</label>
                        	</p>
                            <p style="clear:both;">
								<label for="displayed_1" class="conf_display"><img src="{$img_dir}displayed.png" alt="{l s='Display' mod='videodesk'}" title="{l s='Display' mod='videodesk'}" /></label><input type="radio" class="radio conf_display" name="displayed" id="displayed_1" value="1" {if $shop.configuration.displayed == 1}checked="checked" {/if}/> <label for="displayed_1" class="conf_display_text">{l s='Display:' mod='videodesk'}</label>
                            </p>
                            <p style="clear:both;margin-left:100px;">
	                            <input type="radio" class="radio" name="display_for_all" id="display_for_all_1" value="1" {if $shop.configuration.display_for_all == 1}checked="checked" {/if}/> <label for="display_for_all_1">{l s='for all visitors' mod='videodesk'}</label>
	                            <input type="radio" class="radio" name="display_for_all" id="display_for_all_0" value="0" {if $shop.configuration.display_for_all == 0}checked="checked" {/if}/> <label for="display_for_all_0">{l s='for test IP only' mod='videodesk'}</label>
	                            <input type="text" style="margin: 2px 0 0 0;" name="display_ips" id="display_ips" value="{$shop.configuration.display_ips}" />
                            </p>
                        </div>
                        
                        {if !empty($criterias)}
                        <div class="block criterias clearfix clearBoth">
                            <p class="title">{l s='Videodesk Display Criterias' mod='videodesk'}<span class="infobulle_conf"><span class="infobulle" title="{l s='Help Display Criterias Configuration title' mod='videodesk'}|{l s='Help Display Criterias Configuration content' mod='videodesk'}"> ? </span></span></p>
                            <div class="active">
                                <input type="radio" class="radio" name="criterias" id="criterias_0" value="0" {if $shop.configuration.criterias == 0}checked="checked" {/if}/> <label for="criterias_0" style="margin: 0 20px 0 0;">{l s='Always displayed' mod='videodesk'}</label>
                                <p>
                                <input type="radio" class="radio" name="criterias" id="criterias_1" value="1" {if $shop.configuration.criterias == 1}checked="checked" {/if}/> <label for="criterias_1">{l s='If' mod='videodesk'}</label>
                                <select name="criterias_all_conditions" id="criterias_all_conditions">
                                    <option value="0"{if $shop.configuration.criterias_all_conditions == 0} selected="selected"{/if}>{l s='any' mod='videodesk'}</option>
                                    <option value="1"{if $shop.configuration.criterias_all_conditions == 1} selected="selected"{/if}>{l s='all' mod='videodesk'}</option>
                                </select>
                                <label for="criterias_all_conditions">{l s='of the following rules is satisfied:' mod='videodesk'}</label>
                                </p>
                            </div>
                            
                            {foreach from=$criterias item='criteria'}
                            <div class="criteria clearfix">
                                <input type="checkbox" class="checkbox" name="criteria[{$criteria.id_criteria}]" id="criteria_{$criteria.id_criteria}" value="1" {if $criteria.active == 1}checked="checked" {/if}/> <label for="criteria_{$criteria.id_criteria}">{l s=$criteria.name mod='videodesk'}</label>
                                {if $criteria.with_value == 1}
                                {capture assign="criteria_value"}{$criteria.name}_value{/capture}
                                <input class="text" name="criteria_value[{$criteria.id_criteria}]" id="criteria_value_{$criteria.id_criteria}" value="{$criteria.value}" style="text-align: center;" /> <label for="criteria_value_{$criteria.id_criteria}">{if $criteria.id_criteria == 2 || $criteria.id_criteria == 3}{$currencySign}{else}{l s=$criteria_value mod='videodesk' sprintf=$currencySign}{/if}</label>
                                {/if}
                            </div>
                            {/foreach}
                        </div>
                        {/if}
                        
                        {if !empty($groups_pages)}
                        <div class="block clearfix clearBoth">
                            <p class="title">{l s='Videodesk Display Scope' mod='videodesk'}<span class="infobulle_conf"><span class="infobulle" title="{l s='Help Display Scope Configuration title' mod='videodesk'}|{l s='Help Display Scope Configuration content' mod='videodesk'}"> ? </span></span></p>
                            <p><input type="radio" class="radio" name="scope" id="scope_0" value="0" {if $shop.configuration.scope == 0}checked="checked" {/if}/> <label for="scope_0">{l s='All pages' mod='videodesk'}</label></p>
                            <p style="clear: both;"><input type="radio" class="radio" name="scope" id="scope_1" value="1" {if $shop.configuration.scope == 1}checked="checked" {/if}/> <label for="scope_1">{l s='The following pages:' mod='videodesk'}</label></p>
                            <br class="clearBoth" />
                            <div class="scope clearfix">
                                    {foreach from=$groups_pages item='group_pages' name='groupsForeach'}
                                {if $smarty.foreach.groupsForeach.index > 0 && $smarty.foreach.groupsForeach.index % 3 == 0}<div class="line"></div>{/if}
                                <div class="group clearfix">
                                    {capture assign="group_pages_name"}{$group_pages.name}{/capture}
                                    <p class="title">{l s=$group_pages_name|capitalize mod='videodesk'}</p>
                                        {if !empty($group_pages.pages)}
                                    <div class="pages clearfix">
                                        <input type="checkbox" class="check" id="check{$group_pages.id_group_pages}" value="1" /> <label for="check{$group_pages.id_group_pages}" id="label_check{$group_pages.id_group_pages}" class="check">{l s='Check All' mod='videodesk'}</label>
                                            {if $group_pages.name == 'categories'}
                                        {capture assign='categoriesPath'}{$path_module}shop-configuration-categories.tpl{/capture}
                                        {include file=$categoriesPath categories=$group_pages.pages}
                                            {elseif $group_pages.name == 'cms'}
                                        {capture assign='cmsPath'}{$path_module}shop-configuration-cms.tpl{/capture}
                                        {include file=$cmsPath pages=$group_pages.pages}
                                            {elseif $group_pages.name == 'modules'}
                                        {capture assign='modulesPath'}{$path_module}shop-configuration-modules.tpl{/capture}
                                        {include file=$modulesPath pages=$group_pages.pages}
                                            {else}
                                                {foreach from=$group_pages.pages item='page'}
                                                {if !isset($page.active)}{$group_pages.name}{/if}
                                        <input type="checkbox" class="checkbox" name="pages[{$page.name}]" id="pages_{$page.name}" value="1" {if $page.active == 1}checked="checked" {/if}/> <label for="pages_{$page.name}">{l s=$page.name|capitalize mod='videodesk'}</label>
                                                {/foreach}
                                            {/if}
                                    </div>
                                        {/if}
                                </div>
                                {/foreach}
                            </div>
                        </div>
                        {/if}
                        
                        <div class="block clearfix clearBoth" style="margin-top:0;">
                        	<p class="title">{l s='Videodesk Statistics' mod='videodesk'} <span class="infobulle_conf"><span class="infobulle" title="{l s='Help Statistics Configuration title' mod='videodesk'}|{l s='Help Statistics Configuration content' mod='videodesk'}"> ? </span></span></p>
                            <div class="criteria clearfix">
                                <input type="checkbox" class="checkbox" name="track_stats" id="track_stats" value="1" {if $shop.configuration.track_stats == 1}checked="checked" {/if}/> <label for="track_stats">{l s='Transmit performance statistics to Videodesk' mod='videodesk'}</label>
                            </div>
                        </div>
                    </div>
                </div>
                {/foreach}
                {if sizeof($shops) > 1}
            </div>
                {/if}
            {/foreach}
            
            <input type="submit" class="blueButton" value="{l s='Submit' mod='videodesk'}" />
            <a href="{$return_url}" class="whiteButton">{l s='Cancel' mod='videodesk'}</a>
        </form>
    </div>
    {/if}
</div>

{include file=$module_help lang_iso=$lang_iso}