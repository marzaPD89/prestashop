<?php /* Smarty version Smarty-3.1.19, created on 2015-08-28 17:04:23
         compiled from "C:\xampp\htdocs\php\prestashop\modules\videodesk\\views\templates\admin\bo-videodesk.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3180355e078773b9f37-93484702%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '873cfde51288bbf4ce51f3aeae56b15f173f7d0d' => 
    array (
      0 => 'C:\\xampp\\htdocs\\php\\prestashop\\modules\\videodesk\\\\views\\templates\\admin\\bo-videodesk.tpl',
      1 => 1434966700,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3180355e078773b9f37-93484702',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'employee_firstname' => 0,
    'employee_lastname' => 0,
    'employee_email' => 0,
    'lang_iso' => 0,
    'module_version' => 0,
    'js_url' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55e078773deab3_51038552',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55e078773deab3_51038552')) {function content_55e078773deab3_51038552($_smarty_tpl) {?><!-- < videodesk -->
<script type="text/javascript">
	var _videodesk= _videodesk || {};
	_videodesk['firstname'] = '<?php echo $_smarty_tpl->tpl_vars['employee_firstname']->value;?>
' ;
	_videodesk['lastname'] = '<?php echo $_smarty_tpl->tpl_vars['employee_lastname']->value;?>
' ;
	_videodesk['email'] = '<?php echo $_smarty_tpl->tpl_vars['employee_email']->value;?>
' ;
	_videodesk['lang'] = '<?php echo $_smarty_tpl->tpl_vars['lang_iso']->value;?>
' ;
	_videodesk['module'] = 'prestashop' ;
	_videodesk['module_version'] = '<?php echo $_smarty_tpl->tpl_vars['module_version']->value;?>
' ;
	_videodesk['url'] = '<?php echo $_smarty_tpl->tpl_vars['js_url']->value;?>
';

	(function() {
		var videodesk = document.createElement('script'); videodesk.type =
		'text/javascript'; videodesk.async = true;
		videodesk.src = ('https:' == document.location.protocol ? 'https://' :
		'http://') + _videodesk['url'];
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(videodesk, s);
	})();
</script>
<!-- > videodesk --><?php }} ?>
