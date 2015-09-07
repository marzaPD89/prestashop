<?php /* Smarty version Smarty-3.1.19, created on 2015-05-15 15:10:45
         compiled from "C:\xampp\htdocs\php\prestashop\modules\mymodcomments\views\templates\hook\getContent.tpl" */ ?>
<?php /*%%SmartyHeaderCode:289275555f055c30b44-80888058%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '12b3835a06d45006f91adb86e54bd962e2833cde' => 
    array (
      0 => 'C:\\xampp\\htdocs\\php\\prestashop\\modules\\mymodcomments\\views\\templates\\hook\\getContent.tpl',
      1 => 1413766066,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '289275555f055c30b44-80888058',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'confirmation' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5555f055c3e4d5_69939878',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5555f055c3e4d5_69939878')) {function content_5555f055c3e4d5_69939878($_smarty_tpl) {?><?php if (isset($_smarty_tpl->tpl_vars['confirmation']->value)) {?>
    <div class="alert alert-success"><?php echo smartyTranslate(array('s'=>'Settings updated','mod'=>'mymodcomments'),$_smarty_tpl);?>
</div>
<?php }?>

<?php }} ?>
