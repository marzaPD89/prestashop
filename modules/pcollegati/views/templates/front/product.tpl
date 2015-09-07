{if count($relatedProducts) != 0}
<div class="cpcProductsTitle">{l s='Prodotto collegati:' mod='pcollegati'}</div>
<div class="cpcProducts">
	{foreach $relatedProducts as $product}
		<div class="product col-sm-2 col-xs-12">
			<a href="{$link->getProductLink($product->id, $product->link_rewrite)|escape:'htmlall':'UTF-8'}" class="imgBlock">
				<span class="img">
					{$notIdImage = $product->id|cat:'-' }
					{if $product->id_image != $notIdImage}
						<img src="{$link->getImageLink($product->link_rewrite, $product->id_image, "pcollegati")}" alt="{$product->name}" />
					{else}
						<img src="{$link->getImageLink($product->link_rewrite, 'it-default', "pcollegati")}" alt="{$product->name}" />
					{/if}
				</span>
				{if $displayName}
				<span class="name"><b>{$product->reference}</b></span>
				<span class="name">{$product->description_short}</span>
				{/if}
				{if $displayPrice && !$PS_CATALOG_MODE && $product->show_price}
				<span class="price">
					{convertPrice price=$product->price}
					{if $displayReduction && $product->priceWhitoutReduction != $product->price}
						<span class="priceWhitoutReduction">{convertPrice price=$product->priceWhitoutReduction}</span>
					{/if}
				</span>
				{/if}
			</a>
		</div>
	{/foreach}
	<div class="clear"></div>
</div>
<div class="clear"></div>
{/if}
