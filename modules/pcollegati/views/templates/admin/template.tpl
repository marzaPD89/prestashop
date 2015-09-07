<div>
	<style>
		#product-tab-content-ModulePcollegati .filter {
			background: transparent;
		}
		#collegati {
			width: 400px;
		}
		.list table {
			border-collapse: collapse;
			border-spacing: 2px;
			border-color: gray;
			border: 1px solid #DFD5C3;
			padding: 0;
		}
		.list table tr th {
			background: #F4E6C9;
			padding: 5px;
		}
		.list table tr th, .list table tr td {
			border-bottom: 1px solid #DEDEDE;
		}
		.list table tbody tr:nth-child(even) {
			background-color: #EFEFEF;
		}
		.list table td, .list table th {
			padding: 0 5px;
		}
		.list .action {
			text-talign: center;
		}
		.list .img img {
			width: 35px;
			padding: 3px;
		}
		.list .name {
			width: 300px;
			padding-left: 5px;
		}
		.list b {
			float: left;
			display: block;
			width: 200px;
			text-align: right;
			padding: 0.2em 0.5em 0 0;
		}
	</style>
	<div class="filter" style="margin-bottom: 50px; margin-top: 20px;">
		<label for="filter">{l s='Search a product: ' mod='pcollegati'}</label><input id="collegati" type="text" />
	</div>
	
	<div class="list">
	{if count($relatedProducts) != 0}
	<b>{l s='Products related:' mod='pcollegati'}</b>
		<table>
			<thead>
				<tr>
					<th></td>
					<th class="name">{l s='Product name' mod='pcollegati'}</td>
					<th class="action">{l s='Action' mod='pcollegati'}</td>
				</tr>
			</thead>
			<tbody>
				{foreach $relatedProducts as $product}
					<tr>
						<td class="img"><img src="{$product->img}" alt="logo" /></td>
						<td class="name">{$product->name}</td>
						<td class="action">
							<a href="#" class="remove" productid="{$product->id}">{l s='Remove' mod='pcollegati'}</a>
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	{/if}
	</div>
</div>