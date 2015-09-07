<form method="post" id="related-form">
	<fieldset style="margin-top: 20px;">
		<legend>{l s='Configuration' mod='pcollegati'}</legend>
		<div style="margin: 10px;">
			<label style="width: 400px;" for="madef_advanced_related_two_way">{l s='Apply in both directions the Relations between products: ' mod='pcollegati'}</label>
			<input id="madef_advanced_related_two_way" name="madef_advanced_related_two_way" type="checkbox" {if $twoWay}checked="checked"{/if} value="1" />
		</div>
		<div class="clear" style="margin: 10px;">
			<label style="width: 400px;" for="madef_advanced_related_nb_products">{l s='Number of related products to show on the product page: ' mod='pcollegati'}</label>
			<input id="madef_advanced_related_nb_products" name="madef_advanced_related_nb_products" type="text" value="{$nbProducts}" />
		</div>
		<div style="margin: 10px;">
			<label style="width: 400px;" for="madef_advanced_related_display_name">{l s='Display product name' mod='pcollegati'}</label>
			<input id="madef_advanced_related_display_name" name="madef_advanced_related_display_name" type="checkbox" {if $displayName}checked="checked"{/if} value="1" />
		</div>
		<div style="margin: 10px;">
			<label style="width: 400px;" for="madef_advanced_related_display_price">{l s='Display product price' mod='pcollegati'}</label>
			<input id="madef_advanced_related_display_name" name="madef_advanced_related_display_price" type="checkbox" {if $displayPrice}checked="checked"{/if} value="1" />
		</div>
		<div style="margin: 10px;">
			<label style="width: 400px;" for="madef_advanced_related_display_reduction">{l s='Display product price before reduction' mod='pcollegati'}</label>
			<input id="madef_advanced_related_display_reduction" name="madef_advanced_related_display_reduction" type="checkbox" {if $displayReduction}checked="checked"{/if} value="1" />
		</div>
		<div style="margin: 10px;">
			<label style="width: 400px;" for="madef_advanced_related_display_buy">{l s='Display add to cart button' mod='pcollegati'}</label>
			<input id="madef_advanced_related_display_buy" name="madef_advanced_related_display_buy" type="checkbox" {if $displayBuy}checked="checked"{/if} value="1" />
		</div>
		<div class="clear" style="margin: 10px;margin-left: 415px;">
			<input style="padding: 2px 5px;" type="submit" value="{l s='Save settings' mod='pcollegati'}" name="submitSettings" />
		</div>
	</fieldset>
</form>