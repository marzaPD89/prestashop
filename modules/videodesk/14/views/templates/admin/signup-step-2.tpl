<div id="ajax_wait"></div>

<div id="vd_content">

<div id="vd_sign_up_steps">
	<ul>
		<li>1 {l s="Create account" mod="videodesk"}</li>
		<li class="active">2 {l s="Pick users" mod="videodesk"}</li>
		<li>3 {l s="Create users" mod="videodesk"}</li>
	</ul>
</div>
<div style="clear: both;" />

<div id="errors" class="error">
{if $errors|@count > 0}
	
		<ul>
			{foreach from=$errors item=error}
				<li>{$error}</li>
			{/foreach}
		</ul>
	
{/if}
</div>

<div>
	<!--  step2 -->
	<div id="vd_sign_up">
            <h2><span class="step">2 </span>{l s='Pick user accounts who will use or administrate videodesk' mod='videodesk'}</h2>
                    <p><span class="introtext">{l s='Do you want to import other users in videodesk and create them an account?' mod='videodesk'}</span>
                        <span class="infobulle" title="{l s='Pick Users Help title' mod='videodesk'}|{l s='Pick Users Help content' mod='videodesk'}"> ? </span></p>
                    <p style="clear:both;"><br /></p>
                    <form method="post" id="account_creation_form" novalidate="novalidate" >
                        {assign var="colwidth" value=200}
                        {*assign var="colwidthtotal" value=$colwidth*($shops_group.shops|@count+3)*}
                        {assign var="colwidthtotal" value=$colwidth*($nb_shops+1)+10}
                        <table id="videodesk_employee" class="table" style="width:{$colwidthtotal|ceil}px;" cellspacing="0" cellpadding="0">
<!--                         <table id="videodesk_employee" class="table" style="width:0px;" cellspacing="0" cellpadding="0"> -->
                        {foreach from=$shops item='shops_group'}
                            {*assign var="colwidth" value=900/($shops_group.shops|@count+1)*}
                                <tbody width="100%">
                                <tr> 

                                    <td class="checkall ckeckalltext">
                                        
                                    </td>
                                    {foreach from=$shops_group.shops key=keyshop item='shop'}
                                        <td class="checkall" style="width:{$colwidth|ceil}px">
                                        <span class="ckeckalltext">{l s='check all' mod='videodesk'}</span><br />
                                        <input type='checkbox' name='allshop' class="allshop shop{$shop.id_shop} checkbox" value=''>
                                        </td>
                                    {/foreach}
                                </tr>
                                    {foreach from=$employees key=keyemployee item='employee'}
                                        <tr><td style="width:{$colwidth|ceil}px" class="th"><span>{$employee.name}</span>
                                            
                                        {foreach from=$shops_group.shops key=keyshop item='shop'}
                                            <td style="width:{$colwidth|ceil}px"><input type='checkbox' name='shop[{$employee.id_employee}][]' class="checkbox shop{$shop.id_shop} employee{$employee.id_employee}" value='{$shop.id_shop}' {if $employee.id_employee==$idCurrentEmployee} disabled="disabled" checked="checked"{/if}>
                                                {if $employee.id_employee==$idCurrentEmployee}<input type='checkbox' name='shop[{$employee.id_employee}][]' value='{$shop.id_shop}' style="display:none;" checked="checked">{/if} 
                                            </td>
                                        {/foreach}
                                        </tr>
                                    {/foreach}
                                </tbody>
                        {/foreach}
                    </table>
                    <p>
                    	<input type="submit" class="blueButton" name="submitSignUp_Step2" value="{l s='Import' mod='videodesk'}" />
                    </p>            
                </form>
	</div>
<!--  step2 -->
</div>

</div>


<script type="text/javascript">
{literal}
	$(document).ready(function() {

		// Tooltips
		$('span.infobulle').cluetip({splitTitle: '|'});

		// Shop Checkboxes
		$(".allshop").click(function() {
			var id_shop=$(this).attr("class");
			var check = $(this).attr("checked");
			id_shop = id_shop.replace("allshop ", "");
			id_shop = id_shop.replace("checkbox", "");
			id_shop = id_shop.replace(" ", "");
			
			$("."+id_shop).each(function( index ) {
				if (!$(this).is(':disabled')) {
					if (check=="checked")
						$(this).attr('checked', true);
					else {
						$(this).attr('checked', false);
						$(".allemployee").each(function( index ) {
							$(this).attr('checked', false);
						});
						$(".allcheckall").attr('checked', false);
					}
				}
			});
		});
                    
                $(".allcheckall").click(function() {
			var check = $(this).attr("checked");
                    $(".checkbox").each(function( index ) {
                            if (check=="checked")
                                    $(this).attr('checked', true);
                            else {
                            	if (!$(this).is(':disabled')) {
                                    $(this).attr('checked', false);
                            	}
                            }
                    });
                });
		
	});
{/literal}
</script>

{include file=$module_help lang_iso=$lang_iso}