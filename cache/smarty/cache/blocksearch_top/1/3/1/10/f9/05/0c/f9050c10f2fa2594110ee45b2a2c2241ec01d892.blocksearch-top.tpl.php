<?php /*%%SmartyHeaderCode:78665540cec31b0819-48261188%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f9050c10f2fa2594110ee45b2a2c2241ec01d892' => 
    array (
      0 => 'C:\\xampp\\htdocs\\php\\prestashop\\themes\\default-bootstrap\\modules\\blocksearch\\blocksearch-top.tpl',
      1 => 1424689078,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '78665540cec31b0819-48261188',
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5540cedd5e4306_17048957',
  'has_nocache_code' => false,
  'cache_lifetime' => 31536000,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5540cedd5e4306_17048957')) {function content_5540cedd5e4306_17048957($_smarty_tpl) {?><!-- Block search module TOP -->
<div id="search_block_top" class="col-sm-4 clearfix">
	<form id="searchbox" method="get" action="//localhost/php/prestashop/ricerca" >
		<input type="hidden" name="controller" value="search" />
		<input type="hidden" name="orderby" value="position" />
		<input type="hidden" name="orderway" value="desc" />
		<input class="search_query form-control" type="text" id="search_query_top" name="search_query" placeholder="Cerca" value="" />
		<button type="submit" name="submit_search" class="btn btn-default button-search">
			<span>Cerca</span>
		</button>
	</form>
</div>
<!-- /Block search module TOP --><?php }} ?>
