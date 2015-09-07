<?php /* Smarty version Smarty-3.1.19, created on 2015-07-20 10:30:09
         compiled from "C:\xampp\htdocs\php\prestashop\admin492whe35q\themes\default\template\helpers\list\list_action_default.tpl" */ ?>
<?php /*%%SmartyHeaderCode:389855acb191b0f814-30979923%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7531ad87b13b5528626d0415e3e709e92b583441' => 
    array (
      0 => 'C:\\xampp\\htdocs\\php\\prestashop\\admin492whe35q\\themes\\default\\template\\helpers\\list\\list_action_default.tpl',
      1 => 1424689076,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '389855acb191b0f814-30979923',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'href' => 0,
    'action' => 0,
    'name' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55acb191b22ae2_88322283',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55acb191b22ae2_88322283')) {function content_55acb191b22ae2_88322283($_smarty_tpl) {?>
<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['href']->value, ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
"<?php if (isset($_smarty_tpl->tpl_vars['name']->value)) {?> name="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['name']->value, ENT_QUOTES, 'UTF-8', true);?>
"<?php }?> class="default">
	<i class="icon-asterisk"></i> <?php echo $_smarty_tpl->tpl_vars['action']->value;?>

</a><?php }} ?>
