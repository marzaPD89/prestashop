<?php /*%%SmartyHeaderCode:91955555f18993c0c5-86048639%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd3836144f9777937aa1be6b2c1452c9cd6634fa1' => 
    array (
      0 => 'C:\\xampp\\htdocs\\php\\prestashop\\themes\\default-bootstrap\\modules\\blockspecials\\blockspecials.tpl',
      1 => 1424689078,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '91955555f18993c0c5-86048639',
  'variables' => 
  array (
    'link' => 0,
    'special' => 0,
    'PS_CATALOG_MODE' => 0,
    'priceDisplay' => 0,
    'specific_prices' => 0,
    'priceWithoutReduction_tax_excl' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5555f189a19b91_72865860',
  'cache_lifetime' => 31536000,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5555f189a19b91_72865860')) {function content_5555f189a19b91_72865860($_smarty_tpl) {?>
<!-- MODULE Block specials -->
<div id="special_block_right" class="block">
	<p class="title_block">
        <a href="http://localhost/php/prestashop/calo-prezzi" title="Speciali">
            Speciali
        </a>
    </p>
	<div class="block_content products-block">
    		<ul>
        	<li class="clearfix">
            	<a class="products-block-image" href="http://localhost/php/prestashop/abiti-estivi/7-abito-stampato-chiffon.html">
                    <img 
                    class="replace-2x img-responsive" 
                    src="http://localhost/php/prestashop/20-small_default/abito-stampato-chiffon.jpg" 
                    alt="" 
                    title="Abito stampato in chiffon" />
                </a>
                <div class="product-content">
                	<h5>
                        <a class="product-name" href="http://localhost/php/prestashop/abiti-estivi/7-abito-stampato-chiffon.html" title="Abito stampato in chiffon">
                            Abito stampato in chiffon
                        </a>
                    </h5>
                                        	<p class="product-description">
                            Abito stampato al ginocchio in...
                        </p>
                                        <div class="price-box">
                    	                        	<span class="price special-price">
                                                                    20,01 €                            </span>
                                                                                                                                 <span class="price-percent-reduction">-20%</span>
                                                                                         <span class="old-price">
                                                                    25,01 €                            </span>
                                            </div>
                </div>
            </li>
		</ul>
		<div>
			<a 
            class="btn btn-default button button-small" 
            href="http://localhost/php/prestashop/calo-prezzi" 
            title="Tutte le offerte speciali">
                <span>Tutte le offerte speciali<i class="icon-chevron-right right"></i></span>
            </a>
		</div>
    	</div>
</div>
<!-- /MODULE Block specials -->
<?php }} ?>
