<div class="form-group clearfix">
	<label class="col-lg-2">{l s='Add an ID Product' mod='credittokenpayment'}</label>
	<input class="col-lg-2" type="text" id="products_1" name="products_1" {if isset($idprd)}value="{$idprd}"{/if}>
	<input class="btn btn-default" id="addprd" type="submit" name="add-product" value="{l s='Add another product' mod='credittokenpayment'}">
</div>

{literal}
<script type="text/javascript">
	
	jQuery(function($) {

		$('#addprd').submit(function() {
			return false;
		}); 

	});

</script>
{/literal}