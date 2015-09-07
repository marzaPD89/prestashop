<?php /* Smarty version Smarty-3.1.19, created on 2015-05-15 15:15:53
         compiled from "C:\xampp\htdocs\php\prestashop\modules\mymodcomments\views\templates\front\list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:269095555f189e8a1c9-21013740%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f8e534dc3a64b887fbfc21e1eda31c9c2b2c2996' => 
    array (
      0 => 'C:\\xampp\\htdocs\\php\\prestashop\\modules\\mymodcomments\\views\\templates\\front\\list.tpl',
      1 => 1413766066,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '269095555f189e8a1c9-21013740',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'product' => 0,
    'comments' => 0,
    'comment' => 0,
    'nb_pages' => 0,
    'count' => 0,
    'page' => 0,
    'params' => 0,
    'link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5555f189f40727_17922963',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5555f189f40727_17922963')) {function content_5555f189f40727_17922963($_smarty_tpl) {?><h1>
    <?php echo smartyTranslate(array('s'=>'Comments on product','mod'=>'mymodcomments'),$_smarty_tpl);?>

	"<?php echo $_smarty_tpl->tpl_vars['product']->value->name;?>
"
</h1>

<div class="rte">
<?php  $_smarty_tpl->tpl_vars['comment'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['comment']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['comments']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['comment']->key => $_smarty_tpl->tpl_vars['comment']->value) {
$_smarty_tpl->tpl_vars['comment']->_loop = true;
?>
	<div class="mymodcomments-comment">
		<img src="http://www.gravatar.com/avatar/<?php echo md5(strtolower(trim($_smarty_tpl->tpl_vars['comment']->value['email'])));?>
?s=45" class="pull-left img-thumbnail mymodcomments-avatar" />
		<div><?php echo $_smarty_tpl->tpl_vars['comment']->value['firstname'];?>
 <?php echo substr($_smarty_tpl->tpl_vars['comment']->value['lastname'],0,1);?>
. <small><?php echo substr($_smarty_tpl->tpl_vars['comment']->value['date_add'],0,10);?>
</small></div>
		<div class="star-rating"><i class="glyphicon glyphicon-star"></i> <strong><?php echo smartyTranslate(array('s'=>'Grade:','mod'=>'mymodcomments'),$_smarty_tpl);?>
</strong></div> <input value="<?php echo $_smarty_tpl->tpl_vars['comment']->value['grade'];?>
" type="number" class="rating" min="0" max="5" step="1" data-size="xs" />
		<div><i class="glyphicon glyphicon-comment"></i> <strong><?php echo smartyTranslate(array('s'=>'Comment','mod'=>'mymodcomments'),$_smarty_tpl);?>
 #<?php echo $_smarty_tpl->tpl_vars['comment']->value['id_mymod_comment'];?>
:</strong> <?php echo $_smarty_tpl->tpl_vars['comment']->value['comment'];?>
</div>
	</div>
	<hr />
<?php } ?>
</div>


<ul class="pagination">
    <?php $_smarty_tpl->tpl_vars['count'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['count']->step = 1;$_smarty_tpl->tpl_vars['count']->total = (int) ceil(($_smarty_tpl->tpl_vars['count']->step > 0 ? $_smarty_tpl->tpl_vars['nb_pages']->value+1 - (1) : 1-($_smarty_tpl->tpl_vars['nb_pages']->value)+1)/abs($_smarty_tpl->tpl_vars['count']->step));
if ($_smarty_tpl->tpl_vars['count']->total > 0) {
for ($_smarty_tpl->tpl_vars['count']->value = 1, $_smarty_tpl->tpl_vars['count']->iteration = 1;$_smarty_tpl->tpl_vars['count']->iteration <= $_smarty_tpl->tpl_vars['count']->total;$_smarty_tpl->tpl_vars['count']->value += $_smarty_tpl->tpl_vars['count']->step, $_smarty_tpl->tpl_vars['count']->iteration++) {
$_smarty_tpl->tpl_vars['count']->first = $_smarty_tpl->tpl_vars['count']->iteration == 1;$_smarty_tpl->tpl_vars['count']->last = $_smarty_tpl->tpl_vars['count']->iteration == $_smarty_tpl->tpl_vars['count']->total;?>
        <?php $_smarty_tpl->tpl_vars['params'] = new Smarty_variable(array('module_action'=>'list','product_rewrite'=>$_smarty_tpl->tpl_vars['product']->value->link_rewrite,'id_product'=>$_GET['id_product'],'page'=>$_smarty_tpl->tpl_vars['count']->value), null, 0);?>
        <?php if ($_smarty_tpl->tpl_vars['page']->value!=$_smarty_tpl->tpl_vars['count']->value) {?>
            <li><a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getModuleLink('mymodcomments','comments',$_smarty_tpl->tpl_vars['params']->value);?>
"><span><?php echo $_smarty_tpl->tpl_vars['count']->value;?>
</span> </a></li>
        <?php } else { ?>
            <li class="active current"><span><span><?php echo $_smarty_tpl->tpl_vars['count']->value;?>
</span></span> </li>
        <?php }?>
    <?php }} ?>
</ul><?php }} ?>
