<?php /* Smarty version Smarty-3.1.19, created on 2015-04-29 14:29:58
         compiled from "C:\xampp\htdocs\php\prestashop\themes\default-bootstrap\modules\blockcontact\nav.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8285540cec642cb50-45632774%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1b7dbc46a431e0f21865558cd010833ba9108946' => 
    array (
      0 => 'C:\\xampp\\htdocs\\php\\prestashop\\themes\\default-bootstrap\\modules\\blockcontact\\nav.tpl',
      1 => 1424689078,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8285540cec642cb50-45632774',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'link' => 0,
    'telnumber' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5540cec6441b04_95692984',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5540cec6441b04_95692984')) {function content_5540cec6441b04_95692984($_smarty_tpl) {?>
<div id="contact-link">
	<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('contact',true), ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smartyTranslate(array('s'=>'Contact us','mod'=>'blockcontact'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Contact us','mod'=>'blockcontact'),$_smarty_tpl);?>
</a>
</div>
<?php if ($_smarty_tpl->tpl_vars['telnumber']->value) {?>
	<span class="shop-phone">
		<i class="icon-phone"></i><?php echo smartyTranslate(array('s'=>'Call us now:','mod'=>'blockcontact'),$_smarty_tpl);?>
 <strong><?php echo $_smarty_tpl->tpl_vars['telnumber']->value;?>
</strong>
	</span>
<?php }?><?php }} ?>
