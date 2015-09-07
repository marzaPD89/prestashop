<div class="blocStats">
	<h2><span><img src="{$module_dir}logo.gif" /></span> {$module_name}</h2>
	
	{if $nb_calls > 0}
		<h3>{l s="Conversion" mod="videodesk"}</h3>
		<p class="clearfix">
		<div class="blocConversion">
			<span style="float:left;text-align:center;margin-right:10px;padding-top:15px; width:100px;">
				{l s="Visitors" mod="videodesk"}<br />{$nb_visitors}
			</span>
			<span style="float:left;text-align:center;margin-right:10px;">
				<img src="../modules/statsforecast/next.png"><br />{if $nb_visitors_with_call == 0}0 %{else}{($nb_visitors_with_call * 100 / $nb_visitors)|round:"0"} %{/if}<br />
				<img src="../modules/statsforecast/next.png"><br />{if $nb_visitors_with_call == 0}100 %{else}{(($nb_visitors - $nb_visitors_with_call) * 100 / $nb_visitors)|round:"0"} %{/if}<br />
			</span>
			<span style="float:left;text-align:center;margin-right:10px">
				{l s="With Videodesk conversation" mod="videodesk"}<br />{$nb_visitors_with_call}<br />
				{l s="Without" mod="videodesk"}<br />{$nb_visitors - $nb_visitors_with_call}
			</span>
			<span style="float:left;text-align:center;margin-right:10px;">
				<img src="../modules/statsforecast/next.png"><br />{if $nb_calls_conversion.orders == 0}0 %{else}{($nb_calls_conversion.orders * 100 / $nb_calls_conversion.total_orders)|round:"0"} %{/if}<br />
				<img src="../modules/statsforecast/next.png"><br />{if $nb_calls_conversion.orders == 0}100 %{else}{(($nb_calls_conversion.total_orders - $nb_calls_conversion.orders) * 100 / $nb_calls_conversion.total_orders)|round:"0"} %{/if}<br />
			</span>
			<span style="float:left;text-align:center;margin-right:10px">
				{l s="Orders" mod="videodesk"}<br />{$nb_calls_conversion.orders}<br />
				<br />{$nb_calls_conversion.total_orders - $nb_calls_conversion.orders}
			</span>
		</div>
		</p>
		
		<h3 style="float:left;">{l s="Number of calls" mod="videodesk"}</h3>
		<p class="clearfix">
			<table class="table">
				<tr>
					<th style="min-width: 120px;">{l s="Call type" mod="videodesk"}</th>
					<th style="min-width: 120px;">{l s="Number of calls" mod="videodesk"}</th>
				</tr>
				
				{foreach from=$nb_calls_by_type key=k item=call}
				<tr>
					<td>{l s=$call.call_type mod="videodesk"}</td>
					<td align="center">{$call.count}</td>
				</tr>
				{/foreach}
			</table>
		</p>
		
		<h3>{l s="Number of calls by Employee" mod="videodesk"}</h3>
		<p class="clearfix">
			<table class="table">
				<tr>
					<th style="min-width: 120px;">{l s="Employee" mod="videodesk"}</th>
					<th style="min-width: 120px;">{l s="Number of calls" mod="videodesk"}</th>
				</tr>
				
				{foreach from=$nb_calls_by_employee key=k item=call}
				<tr>
					<td>{$call.employee_name}</td>
					<td align="center">{$call.count}</td>
				</tr>
				{/foreach}
			</table>
		</p>		
		
	{else}
		<p>{l s="No calls on this period" mod="videodesk"}</p>
	{/if}
</div>

