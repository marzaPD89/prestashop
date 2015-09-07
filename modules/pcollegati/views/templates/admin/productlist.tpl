{if count($matchedProducts) != 0}
	<ul class="matchedProducts">
		{foreach $matchedProducts as $product}
			<li productid="{$product.id_product}"><img src="{$product.img}" alt="logo" />{$product.name}</li>
		{/foreach}
	</ul>
{/if}