$(function() {
	// 1.4 Hack
	if ($('#current_product').length == 0) {
		return;
	}
	var interval = window.setInterval(function() {
		if ($('.tab-row').length == 0) {
			return;
		}
		if ($('#link-ModulePcollegati').length == 0) {
			$('.tab-row')
				.append(
					$('<h4 class="tab"></h4>')
						.attr('id', 'product-tab-ModulePcollegati')
						.html(
							$('<a href="#"></a>')
								.html(($('h4.tab').length + 1) + '. Related Products')
						)
						.click(function() {
							$('.tab-page').hide();
							$('.tab-row .tab').removeClass('selected');
							$('#product-tab-content-ModulePcollegati').show();
							$(this).addClass('selected');
							return false;
						})
				);
			
			var lock = false;
			$('.tab-row .tab').click(function() {
				if ($(this).attr('id') == 'product-tab-ModulePcollegati') {
					return false;
				}
				if (!lock) {
					lock = true;
					if ($(this).prev().length > 0) {
						console.log('l', $(this).prev());
						$(this).prev().click();
						$(this).click();
					} else {
						console.log('n', $(this).next());
						$(this).next().click();
						$(this).click();
					}
					window.setTimeout(function() {
						lock = false;
					}, 100);
				}
				$('#product-tab-ModulePcollegati').removeClass('selected');
				$('#product-tab-content-ModulePcollegati').hide();
			});
			$('.tab-pane')
				.append(
					$('<div class="tab-page"></div>')
						.attr('id', 'product-tab-content-ModulePcollegati')
				);
			
			loadRelatedProductsPage2();
		}
		window.clearInterval(interval);
	}, 100);
});


var loadRelatedProductsPage2 = function() {
	$.ajax({
		url: COLLEGATI_AJAX_URL,
		data: {
			action: 'template',
			token: COLLEGATI_TOKEN,
			id_product: ID_PRODUCT
		},
		crossDomain: true,
		type: 'POST',
		dataType: 'json',
		success: function(data) {
			$('#product-tab-content-ModulePcollegati').html(data);
			
			var timeout = null;
			$('#product-tab-content-ModulePcollegati #collegati').keydown(function(e) {
				if (timeout != null) {
					window.clearTimeout(timeout);
				}
				timeout = window.setTimeout(function() {
					$.ajax({
						crossDomain: true,
						url: COLLEGATI_AJAX_URL,
						type: 'POST',
						data: {
							action: 'productlist',
							token: COLLEGATI_TOKEN,
							id_product: ID_PRODUCT,
							search: $('#product-tab-content-ModulePcollegati #collegati').val()
						},
						context: $('#product-tab-content-ModulePcollegati #collegati'),
						dataType: 'json',
						success: function(data) {
							var matchedProducts = $(data);
							$('#product-tab-content-ModulePcollegati .matchedProducts').remove();
							$(this).after(matchedProducts);
							matchedProducts.css({
								position: 'absolute',
								top: $(this).position().top + $(this).outerHeight(),
								left: $(this).position().left - 1,
								width: $(this).outerWidth(),
								border: '1px solid gray',
								borderTop: 'none',
								background: 'white',
								listStyle : 'none'
							});
							matchedProducts.find('li').css({
								whiteSpace: 'nowrap',
								overflow: 'hidden',
								padding: '3px'
							});
							matchedProducts.find('li img').css({
								width: '20px',
								marginRight: '3px'
							});
							matchedProducts.find('li').hover(function() {
								$(this).css({
									background: 'lightblue'
								});
								$(this).addClass('selected');
							}, function() {
								$(this).css({
									background: 'white'
								});
								$(this).removeClass('selected');
							});
							matchedProducts.find('li').click(function() {
								$.ajax({
									crossDomain: true,
									url: COLLEGATI_AJAX_URL,
									data: {
										action: 'add',
										token: COLLEGATI_TOKEN,
										id_product: ID_PRODUCT,
										id_product_related: $(this).attr('productid')
									},
									context: $(this),
									type: 'POST',
									dataType: 'json',
									success: function(data) {
										$('#product-tab-content-ModulePcollegati .matchedProducts').remove();
										$('#product-tab-content-ModulePcollegati #collegati').val('');
										$('#product-tab-content-ModulePcollegati .list').replaceWith($(data).find('.list'));
										return true;
									}
								});
							});
							console.log(matchedProducts);
						}
					});
				}, 500);
				return true;
			});
			$('#product-tab-content-ModulePcollegati #collegati').blur(function() {
				if ($('#product-tab-content-ModulePcollegati .matchedProducts li.selected').length != 0) {
					return;
				}
				$('#product-tab-content-ModulePcollegati .matchedProducts').remove();
				return true;
			});
			
			$('#product-tab-content-ModulePcollegati .remove').live('click', function() {
				$.ajax({
					crossDomain: true,	
					url: COLLEGATI_AJAX_URL,
					data: {
						action: 'remove',
						token: COLLEGATI_TOKEN,
						id_product: ID_PRODUCT,
						id_product_related: $(this).attr('productid')
					},
					context: $(this),
					type: 'POST',
					dataType: 'json',
					success: function(data) {
						$('#product-tab-content-ModulePcollegati .list').replaceWith($(data).find('.list'));
					}
				});
				return false;
			});
		}
	});
}