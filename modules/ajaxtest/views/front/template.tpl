<div class="list container">
	{if count($products) > 0}
		{foreach $products as $prod}
			<div>
				<h1>{$prod->name[1]}</h1>
				<p>{$prod->description[1]}</p>
			</div>
		{/foreach}
	{/if}
</div>