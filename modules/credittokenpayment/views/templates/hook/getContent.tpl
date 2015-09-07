{if isset($idprd)}
	<div class="alert alert-success">{l s='Settings updated succesfully' mod='credittokenpayment'}</div>
{/if}

<fieldset id="token_form">
	<h2>{l s='CreditTokenPayment configuration' mod='credittokenpayment'}</h2>
	<div class="panel">
		<div class="panel-heading">
			<legend><img src="../img/admin/cog.gif" alt="" width="16"></legend>
		</div>
		<h3>{l s='Use this form to configurate which products use to buy credits token' mod='credittokenpayment'}</h3>
		<form action="" method="post">
			<h4 class="col-lg-8">{l s='Add an ID Product' mod='credittokenpayment'}</h4>
			<div class="btn btn-default addprd">{l s='Add another product' mod='credittokenpayment'}</div>
			{*<div class="btn btn-default delprd">{l s='Delete a row of product' mod='credittokenpayment'}</div>*}
			{if !isset($queryInit)}
				<div class="form-group clearfix">
					<div class="form-group-left"><label>{l s='ID Product' mod='credittokenpayment'}</label><input type="text" id="products_1" name="products_1"></div>
					<div class="form-group-right"><label>{l s='Credits' mod='credittokenpayment'}</label><input type="text" id="credit_products_1" name="credit_products_1"></div>
					<div class="form-group-delete btn btn-default">{l s='Delete this row' mod='credittokenpayment'}</div>
				</div>
			{else}
				{foreach $queryInit as $row}
					<div class="form-group clearfix">
						<div class="form-group-left"><label>{l s='ID Product' mod='credittokenpayment'}</label><input type="text" id="products_{$row['id_row']}" name="products_{$row['id_row']}" value="{$row['id_product']}"></div>
						<div class="form-group-right"><label>{l s='Credits' mod='credittokenpayment'}</label><input type="text" id="credit_products_{$row['id_row']}" name="credit_products_{$row['id_row']}" value="{$row['credits']}"></div>
						<div class="form-group-delete btn btn-default">{l s='Delete this row' mod='credittokenpayment'}</div>
					</div>
				{/foreach}
			{/if}
			<div class="panel-footer">
				<input class="btn btn-default pull-right" type="submit" name="submit-configuration" value="{l s='Save' mod='credittokenpayment'}">
			</div>
		</form>
	</div>
</fieldset>

<script type="text/javascript">

	$('.form-group-delete').click(function() {
        var url = "{$base_dir}modules/credittokenpayment/ajax-credittokenpayment.php";
        var attr_id_row = $(this).siblings('.form-group-left').children('input').attr('id');
        var id_row = attr_id_row.substr(9);
        var thisInClick = $(this);
        $.ajax({
            url : url,
            type: "get",
            data: "delete=true&id_row="+id_row,
            dataType: "html",
            success : function (data) {
            	thisInClick.parent().remove();
                alert('Cancellazione avvenuta con successo');
            },
            error : function (stato) {
                alert("E' evvenuto un errore. Stato della chiamata: "+stato);
            }
        });
    });

</script>