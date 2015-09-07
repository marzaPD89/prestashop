<?php /* Smarty version Smarty-3.1.19, created on 2015-07-20 10:30:28
         compiled from "C:\xampp\htdocs\php\prestashop\modules\pcollegati\views\templates\admin\template.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2674855acb1a44ae299-14115318%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '05aa97477ee4e540a6e885a389ecdcc9edae54af' => 
    array (
      0 => 'C:\\xampp\\htdocs\\php\\prestashop\\modules\\pcollegati\\views\\templates\\admin\\template.tpl',
      1 => 1412677813,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2674855acb1a44ae299-14115318',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'relatedProducts' => 0,
    'product' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55acb1a451b735_05419604',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55acb1a451b735_05419604')) {function content_55acb1a451b735_05419604($_smarty_tpl) {?><div>
	<style>
		#product-tab-content-ModulePcollegati .filter {
			background: transparent;
		}
		#collegati {
			width: 400px;
		}
		.list table {
			border-collapse: collapse;
			border-spacing: 2px;
			border-color: gray;
			border: 1px solid #DFD5C3;
			padding: 0;
		}
		.list table tr th {
			background: #F4E6C9;
			padding: 5px;
		}
		.list table tr th, .list table tr td {
			border-bottom: 1px solid #DEDEDE;
		}
		.list table tbody tr:nth-child(even) {
			background-color: #EFEFEF;
		}
		.list table td, .list table th {
			padding: 0 5px;
		}
		.list .action {
			text-talign: center;
		}
		.list .img img {
			width: 35px;
			padding: 3px;
		}
		.list .name {
			width: 300px;
			padding-left: 5px;
		}
		.list b {
			float: left;
			display: block;
			width: 200px;
			text-align: right;
			padding: 0.2em 0.5em 0 0;
		}
	</style>
	<div class="filter" style="margin-bottom: 50px; margin-top: 20px;">
		<label for="filter"><?php echo smartyTranslate(array('s'=>'Search a product: ','mod'=>'pcollegati'),$_smarty_tpl);?>
</label><input id="collegati" type="text" />
	</div>
	
	<div class="list">
	<?php if (count($_smarty_tpl->tpl_vars['relatedProducts']->value)!=0) {?>
	<b><?php echo smartyTranslate(array('s'=>'Products related:','mod'=>'pcollegati'),$_smarty_tpl);?>
</b>
		<table>
			<thead>
				<tr>
					<th></td>
					<th class="name"><?php echo smartyTranslate(array('s'=>'Product name','mod'=>'pcollegati'),$_smarty_tpl);?>
</td>
					<th class="action"><?php echo smartyTranslate(array('s'=>'Action','mod'=>'pcollegati'),$_smarty_tpl);?>
</td>
				</tr>
			</thead>
			<tbody>
				<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['relatedProducts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->_loop = true;
?>
					<tr>
						<td class="img"><img src="<?php echo $_smarty_tpl->tpl_vars['product']->value->img;?>
" alt="logo" /></td>
						<td class="name"><?php echo $_smarty_tpl->tpl_vars['product']->value->name;?>
</td>
						<td class="action">
							<a href="#" class="remove" productid="<?php echo $_smarty_tpl->tpl_vars['product']->value->id;?>
"><?php echo smartyTranslate(array('s'=>'Remove','mod'=>'pcollegati'),$_smarty_tpl);?>
</a>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	<?php }?>
	</div>
</div><?php }} ?>
