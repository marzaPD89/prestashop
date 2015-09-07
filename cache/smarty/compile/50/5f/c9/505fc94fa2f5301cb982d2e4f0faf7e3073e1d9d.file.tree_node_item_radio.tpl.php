<?php /* Smarty version Smarty-3.1.19, created on 2015-07-20 10:29:06
         compiled from "C:\xampp\htdocs\php\prestashop\admin492whe35q\themes\default\template\helpers\tree\tree_node_item_radio.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2419355acb15217d460-59544206%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '505fc94fa2f5301cb982d2e4f0faf7e3073e1d9d' => 
    array (
      0 => 'C:\\xampp\\htdocs\\php\\prestashop\\admin492whe35q\\themes\\default\\template\\helpers\\tree\\tree_node_item_radio.tpl',
      1 => 1424689076,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2419355acb15217d460-59544206',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'node' => 0,
    'input_name' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55acb152199696_79683360',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55acb152199696_79683360')) {function content_55acb152199696_79683360($_smarty_tpl) {?>
<li class="tree-item<?php if (isset($_smarty_tpl->tpl_vars['node']->value['disabled'])&&$_smarty_tpl->tpl_vars['node']->value['disabled']==true) {?> tree-item-disable<?php }?>">
	<label class="tree-item-name">
		<input type="radio" name="<?php echo $_smarty_tpl->tpl_vars['input_name']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['node']->value['id_category'];?>
"<?php if (isset($_smarty_tpl->tpl_vars['node']->value['disabled'])&&$_smarty_tpl->tpl_vars['node']->value['disabled']==true) {?> disabled="disabled"<?php }?> />
		<i class="tree-dot"></i>
		<?php echo $_smarty_tpl->tpl_vars['node']->value['name'];?>

	</label>
</li><?php }} ?>
