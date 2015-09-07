<script type="text/javascript">
    var nbStep = {$nbStep};
    var progressBarWidth = {$progressBarWidth};
    var token_videodesk = '{$token_videodesk}';
    var baseDir = '{$baseDir}';
    var module_uri = '{$VD_BO_CONF}';

    {literal}
	$(document).ready(function() {
		$("#vd_sign_in_button").live("click", function() {
			$("#vd_sign_in_button").hide();
			$("#vd_sign_in_form").show();
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
    <div class="configuration">
    	<input type="hidden" name="vd_token" id="vd_token" value="{$token}" />
        {foreach from=$shops item='shops_group'}
            {if sizeof($shops) > 1}
        	<div class="shops_group">
            	<h3>{l s='Group:' mod='videodesk'} {$shops_group.name}</h3>
            {/if}
            {foreach from=$shops_group.shops item='shop'}
            <div class="shop">
                {if sizeof($shops_group.shops) > 1}<h4 class="blueTab">{$shop.name}</h4>{/if}
                <div class="bg_gray content">
                    {if empty($shop.configuration.website_id)}
                        <span id="website_id_{$shop.id_shop}" class="text">{l s='Videodesk' mod='videodesk'} <span class="red">{l s='is not activated' mod='videodesk'}</span> {l s='on your shop yet' mod='videodesk'}</span>
                        <br /><br /><input type="text" class="vd_website_id" id="vd_website_id_{$shop.id_shop}" name="vd_website_id_{$shop.id_shop}" placeholder="{l s='Website ID' mod='videodesk'}" value="" />
                        <input class="blueButton vd_website_id_submit" id="vd_website_id_submit_{$shop.id_shop}" type="submit" name="vd_website_id_submit_{$shop.id_shop}" value="{l s='Validate' mod='videodesk'}" />
                        <span style="float:none!important;" class="infobulle" title="{l s='Home Help title' mod='videodesk'}|{l s='Home Help content' mod='videodesk'}"> ? </span>
                    {else}
                        <div class="form">
                            <input type="text" class="vd_website_id" id="vd_website_id_{$shop.id_shop}" name="vd_website_id_{$shop.id_shop}" placeholder="{l s='Videodesk Id merchant code' mod='videodesk'}" value="{$shop.configuration.website_id}" />
                            <input class="blueButton vd_website_id_submit" id="vd_website_id_submit_{$shop.id_shop}" type="submit" name="vd_website_id_submit_{$shop.id_shop}" value="{l s='Validate' mod='videodesk'}" />
                        </div>
                        <div class="modify">
                            <span>{l s='Website ID:' mod='videodesk'}</span>
                            <span id="website_id_{$shop.id_shop}">{$shop.configuration.website_id} <img src="{$img_dir}valid.png" alt="{l s='Website ID' mod='videodesk'}" /></span>
                            <input style="height:25px; padding: 0 10px;" class="whiteButton vd_website_id_modify" id="vd_website_id_modify_{$shop.id_shop}" type="submit" name="vd_website_id_submit_{$shop.id_shop}" value="{l s='Modify' mod='videodesk'}" />
                        </div>
                    <ul> 
                        {if $shop.configuration.displayed == 0}
                        <li id="display{$shop.id_shop}">
                            <h5><img src="{$img_dir}spot.png" /> {l s='Display the Videodesk module on your shop' mod='videodesk'}</h5>
                            <span class="vd_displayed_text">
                                <img src="{$img_dir}not-displayed.png" alt="{l s='Videodesk' mod='videodesk'} {l s='is not displayed' mod='videodesk'}" /> 
                                {l s='Videodesk' mod='videodesk'} <span class="red">{l s='is not displayed' mod='videodesk'}</span> {l s='on your shop' mod='videodesk'}
                            </span>
                            <input type="submit" id="vd_displayed_submit_{$shop.id_shop}" name="vd_displayed_submit_{$shop.id_shop}" value="{l s='Display' mod='videodesk'}" class="blueButton vd_displayed_submit display" />
                        </li>
                        {/if}
                        <li id="vd_admin{$shop.id_shop}" {if $shop.configuration.displayed == 0}style="display: none;"{/if}>
                            <h5><img src="{$img_dir}spot.png" /> {l s='Set your status available and start discussing with your customers' mod='videodesk'}</h5>
                            <span class="vd_displayed_text">
                                <a href="{$VD_BO_HOME}" target="_blank" class="vd_displayed_text">{$VD_BO_HOME}</a>
                            </span>
                            <a href="{$VD_BO_HOME}" target="_blank" class="blueButton vd_displayed_submit">{l s='Connect' mod='videodesk'}</a>
                        </li>
                        <li class="clearfix">
                            <h5><img src="{$img_dir}spot.png" /> {l s='Customize Videodesk on your shop' mod='videodesk'}</h5>
                            <div class="progressBar" id="progressBar_{$shop.id_shop}">
                                <span class="title">{l s='Customization progress:' mod='videodesk'}</span>
                                <div class="myProgressBar"><div class="progress"></div></div>
                                <span class="percent"><span class="progressBar_number">{$shop.configuration.progressBar}</span> %</span>
                            </div>
                            <div class="custom" id="custom_{$shop.id_shop}">
                               <div class="left clearfix">
                                    <ol>
                                        <li class="clearfix">
                                            <div class="left_text">
                                                <p class="title"><span>1</span> {l s='Display criterias & scope' mod='videodesk'}</p>
                                                <p class="desc">{l s='Choose on which page/product and when you are available' mod='videodesk'}</p>
                                            </div>
                                            <div class="right_text">
                                                <!--<a {if $shop.configuration.progress_criterias == 1}style="display:block;" {else}style="display:none;" {/if}class="done whiteButton noDone" id="done_criterias_{$shop.id_shop}" href="javascript: void(0);">{l s='Unmark as done' mod='videodesk'}</a>-->
                                                <a {if $shop.configuration.progress_criterias == 1}style="display:none;" {else}style="display:block;" {/if}class="done whiteButton isDone" id="done_criterias_{$shop.id_shop}" href="javascript: void(0);">{l s='Mark as done' mod='videodesk'}</a>
                                                <a {if $shop.configuration.progress_criterias == 1}style="margin-right:127px;"{/if} id="go_criterias_{$shop.id_shop}" class="blueButton" href="{$VD_BO_CONF}&id_shop={$shop.id_shop}&old_url={$shopContext}&shopConfiguration">{l s='Go' mod='videodesk'}</a>
                                            </div>
                                        </li>
                                        <li class="clearfix">
                                            <div class="left_text">
                                                <p class="title"><span>2</span> {l s='Colors and position' mod='videodesk'}</p>
                                                <p class="desc">{l s='Choose precise position and all the buttons, background, texts... colors' mod='videodesk'}</p>
                                            </div>
                                            <div class="right_text">
                                                <!--<a {if $shop.configuration.progress_colors == 1}style="display:block;" {else}style="display:none;" {/if}class="done whiteButton noDone" id="done_colors_{$shop.id_shop}" href="javascript: void(0);">{l s='Unmark as done' mod='videodesk'}</a>-->
                                                <a {if $shop.configuration.progress_colors == 1}style="display:none;" {else}style="display:block;" {/if}class="done whiteButton isDone" id="done_colors_{$shop.id_shop}" href="javascript: void(0);">{l s='Mark as done' mod='videodesk'}</a>
                                                <a {if $shop.configuration.progress_colors == 1}style="margin-right:127px;"{/if} id="go_colors_{$shop.id_shop}" class="blueButton" href="{$VD_BO_TEMPLATE}/{$shop.configuration.website_id}{$URL_SUFFIX}" target="_blank">{l s='Go' mod='videodesk'}</a>
                                            </div>
                                        </li>
                                        <li class="clearfix">
                                            <div class="left_text">
                                                <p class="title"><span>3</span> {l s='Texts' mod='videodesk'}</p>
                                                <p class="desc">{l s="Personalize all the module texts" mod='videodesk'}</p>
                                            </div>
                                            <div class="right_text">
                                                <!--<a {if $shop.configuration.progress_texts == 1}style="display:block;" {else}style="display:none;" {/if}class="done whiteButton noDone" id="done_texts_{$shop.id_shop}" href="javascript: void(0);">{l s='Unmark as done' mod='videodesk'}</a>-->
                                                <a {if $shop.configuration.progress_texts == 1}style="display:none;" {else}style="display:block;" {/if}class="done whiteButton isDone" id="done_texts_{$shop.id_shop}" href="javascript: void(0);">{l s='Mark as done' mod='videodesk'}</a>
                                                <a {if $shop.configuration.progress_texts == 1}style="margin-right:127px;"{/if} id="go_texts_{$shop.id_shop}" class="blueButton" href="{$VD_BO_TEXTS}/{$shop.configuration.website_id}{$URL_SUFFIX}" target="_blank">{l s='Go' mod='videodesk'}</a>
                                            </div>
                                        </li>
                                        <li class="clearfix">
                                            <div class="left_text">
                                                <p class="title"><span>4</span> {l s='Pretyped messages' mod='videodesk'}</p>
                                                <p class="desc">{l s='Save and reuse your most often-used responses' mod='videodesk'}</p>
                                            </div>
                                            <div class="right_text">
                                                <!--<a {if $shop.configuration.progress_messages == 1}style="display:block;" {else}style="display:none;" {/if}class="done whiteButton noDone" id="done_messages_{$shop.id_shop}" href="javascript: void(0);">{l s='Unmark as done' mod='videodesk'}</a>-->
                                                <a {if $shop.configuration.progress_messages == 1}style="display:none;" {else}style="display:block;" {/if}class="done whiteButton isDone" id="done_messages_{$shop.id_shop}" href="javascript: void(0);">{l s='Mark as done' mod='videodesk'}</a>
                                                <a {if $shop.configuration.progress_messages == 1}style="margin-right:127px;"{/if} id="go_messages_{$shop.id_shop}" class="blueButton" href="{$VD_BO_MESSAGES}/{$shop.configuration.website_id}{$URL_SUFFIX}" target="_blank">{l s='Go' mod='videodesk'}</a>
                                            </div>
                                        </li>
                                        <li class="clearfix">
                                            <div class="left_text">
                                                <p class="title"><span>5</span> {l s='Agent display rules' mod='videodesk'}</p>
                                                <p class="desc">{l s='Picture of agents, languages, hidden/shown when not available' mod='videodesk'}</p>
                                            </div>
                                            <div class="right_text">
                                                <!--<a {if $shop.configuration.progress_agent == 1}style="display:block;" {else}style="display:none;" {/if}class="done whiteButton noDone" id="done_agent_{$shop.id_shop}" href="javascript: void(0);">{l s='Unmark as done' mod='videodesk'}</a>-->
                                                <a {if $shop.configuration.progress_agent == 1}style="display:none;" {else}style="display:block;" {/if}class="done whiteButton isDone" id="done_agent_{$shop.id_shop}" href="javascript: void(0);">{l s='Mark as done' mod='videodesk'}</a>
                                                <a {if $shop.configuration.progress_agent == 1}style="margin-right:127px;"{/if} id="go_agent_{$shop.id_shop}" class="blueButton" href="{$VD_BO_AGENT}/{$shop.configuration.website_id}{$URL_SUFFIX}" target="_blank">{l s='Go' mod='videodesk'}</a>
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                                <div class="preview">
                                    <h5 class="previewh5">{l s='Preview' mod='videodesk'}</h5>
                                    <iframe class="previewiframe" id="preview_{$shop.id_shop}" src="{$VD_PREVIEW_URL}/{$shop.configuration.website_id}"></iframe>
                                </div>
                            </div>
                            <br class="clearBoth" />
                        </li>
                    </ul>
                    {/if}
                </div>
            </div>
            {/foreach}
            {if sizeof($shops) > 1}
        </div>
            {/if}
        {/foreach}
    </div>
    {/if}
</div>

{include file=$module_help lang_iso=$lang_iso}