{if $show_toolbar}
	{include file="toolbar.tpl" toolbar_btn=$toolbar_btn toolbar_scroll=$toolbar_scroll title=$title}
{/if}

<div id="container-customer" class="infoCustomer">
	<dl><dt>{l s="Call date:" mod="videodesk"}</dt><dd>{dateFormat date=$call->call_date full=1}</dd></dl>
	<dl><dt>{l s="Employee:" mod="videodesk"}</dt><dd>{$employee_name}</dd></dl>
	<dl><dt>{l s="Customer:" mod="videodesk"}</dt><dd>{$customer_name}</dd></dl>
	{if isset($call->id_cart) && $call->id_cart != 0}
	<dl>
		<dt>{l s="Cart:" mod="videodesk"}</dt>
		<dd><a href="index.php?tab=AdminCarts&id_cart={$call->id_cart}&viewcart&token={getAdminToken tab='AdminCarts'}"><img src="../img/admin/details.gif" /></a></dd>
	</dl>
	{/if}
	<dl>
		<dt>{l s="Connection page:" mod="videodesk"}</dt>
		<dd>{$call->connexion_page} <a href="{$call->connexion_page}" target="_blank"><img src="../img/admin/details.gif" alt="Voir"></a></dd>
	</dl>
	
	<br />
	<dl><dt>{l s="Transcript:" mod="videodesk"}</dt></dl><br />
	{$transcript}
	
</div>
