<?php /* Smarty version Smarty-3.1.19, created on 2015-08-28 16:12:16
         compiled from "C:\xampp\htdocs\php\prestashop\admin492whe35q\themes\default\template\helpers\tree\tree_header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2509155e06c404c3899-54762082%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd962e6f5a014ccd8eeae202b7f797924df25a42c' => 
    array (
      0 => 'C:\\xampp\\htdocs\\php\\prestashop\\admin492whe35q\\themes\\default\\template\\helpers\\tree\\tree_header.tpl',
      1 => 1424689076,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2509155e06c404c3899-54762082',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'toolbar' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55e06c404d1557_94249030',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55e06c404d1557_94249030')) {function content_55e06c404d1557_94249030($_smarty_tpl) {?>
<div class="tree-panel-heading-controls clearfix">
	<?php if (isset($_smarty_tpl->tpl_vars['title']->value)) {?><i class="icon-tag"></i>&nbsp;<?php echo smartyTranslate(array('s'=>$_smarty_tpl->tpl_vars['title']->value),$_smarty_tpl);?>
<?php }?>
	<?php if (isset($_smarty_tpl->tpl_vars['toolbar']->value)) {?><?php echo $_smarty_tpl->tpl_vars['toolbar']->value;?>
<?php }?>
</div><?php }} ?>
