jQuery(function($) {

		$('.addprd').click(function() {
			
			var idPrdAdded = $('#token_form .form-group-left').last().children('input').attr('id');
			var res = parseInt(idPrdAdded.match(/\d+/g)); 
			var addText = $(this).text();
			
			res++;

			var html = '<div class="form-group clearfix"><div class="form-group-left"><label>ID Product</label><input id="products_'+res+'" name="products_'+res+'" type="text"></div><div class="form-group-right"><label>Credits</label><input id="credit_products_'+res+'" type="text" name="credit_products_'+res+'"></div><div class="form-group-delete btn btn-default">Delete this row</div></div>';

			$(html).insertBefore('.panel-footer');

		}); 

		/*$('.delprd').click(function() {

			var lengthID = $('#token_form .form-group').length;

			if(lengthID > 1)
				$('.form-group').last().remove();
			else
				alert('Add a row of product, before to remove it');

		});*/

	});