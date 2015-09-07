<?php /* Smarty version Smarty-3.1.19, created on 2015-07-20 10:30:28
         compiled from "C:\xampp\htdocs\php\prestashop\modules\mymodcomments\views\templates\hook\displayAdminProductsExtra.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2628455acb1a482c3e2-49635354%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '74a1f47f25485f62385f24efed78945dea535bef' => 
    array (
      0 => 'C:\\xampp\\htdocs\\php\\prestashop\\modules\\mymodcomments\\views\\templates\\hook\\displayAdminProductsExtra.tpl',
      1 => 1413766066,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2628455acb1a482c3e2-49635354',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'comments' => 0,
    'comment' => 0,
    'nb_pages' => 0,
    'page' => 0,
    'count' => 0,
    'ajax_action_url' => 0,
    'pc_base_dir' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55acb1a4898f25_65240540',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55acb1a4898f25_65240540')) {function content_55acb1a4898f25_65240540($_smarty_tpl) {?><div class=" product-tab-content" id="product-tab-content-mymodcomments" style="display: block;">
	<div class="panel product-tab" id="product-mymodcomments">
		<h3 class="tab"> <i class="icon-info"></i> <?php echo smartyTranslate(array('s'=>'Product Comments','mod'=>'mymodcomments'),$_smarty_tpl);?>
</h3>

		<table style="width:100%">
			<thead>
			<tr>
				<th><?php echo smartyTranslate(array('s'=>'ID','mod'=>'mymodcomments'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyTranslate(array('s'=>'Author','mod'=>'mymodcomments'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyTranslate(array('s'=>'E-mail','mod'=>'mymodcomments'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyTranslate(array('s'=>'Grade','mod'=>'mymodcomments'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyTranslate(array('s'=>'Comment','mod'=>'mymodcomments'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyTranslate(array('s'=>'Date','mod'=>'mymodcomments'),$_smarty_tpl);?>
</th>
			</tr>
			</thead>
			<tbody>
            <?php  $_smarty_tpl->tpl_vars['comment'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['comment']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['comments']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['comment']->key => $_smarty_tpl->tpl_vars['comment']->value) {
$_smarty_tpl->tpl_vars['comment']->_loop = true;
?>
			<tr>
				<td>#<?php echo $_smarty_tpl->tpl_vars['comment']->value['id_mymod_comment'];?>
</td>
				<td><?php echo $_smarty_tpl->tpl_vars['comment']->value['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['comment']->value['lastname'];?>
</td>
				<td><?php echo $_smarty_tpl->tpl_vars['comment']->value['email'];?>
</td>
				<td><?php echo $_smarty_tpl->tpl_vars['comment']->value['grade'];?>
/5</td>
				<td><?php echo $_smarty_tpl->tpl_vars['comment']->value['comment'];?>
</td>
				<td><?php echo $_smarty_tpl->tpl_vars['comment']->value['date_add'];?>
</td>
			</tr>
            <?php } ?>
			</tbody>
		</table>

        <?php if ($_smarty_tpl->tpl_vars['nb_pages']->value>1) {?>
            <ul class="pagination">
            <?php $_smarty_tpl->tpl_vars['count'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['count']->step = 1;$_smarty_tpl->tpl_vars['count']->total = (int) ceil(($_smarty_tpl->tpl_vars['count']->step > 0 ? $_smarty_tpl->tpl_vars['nb_pages']->value+1 - (1) : 1-($_smarty_tpl->tpl_vars['nb_pages']->value)+1)/abs($_smarty_tpl->tpl_vars['count']->step));
if ($_smarty_tpl->tpl_vars['count']->total > 0) {
for ($_smarty_tpl->tpl_vars['count']->value = 1, $_smarty_tpl->tpl_vars['count']->iteration = 1;$_smarty_tpl->tpl_vars['count']->iteration <= $_smarty_tpl->tpl_vars['count']->total;$_smarty_tpl->tpl_vars['count']->value += $_smarty_tpl->tpl_vars['count']->step, $_smarty_tpl->tpl_vars['count']->iteration++) {
$_smarty_tpl->tpl_vars['count']->first = $_smarty_tpl->tpl_vars['count']->iteration == 1;$_smarty_tpl->tpl_vars['count']->last = $_smarty_tpl->tpl_vars['count']->iteration == $_smarty_tpl->tpl_vars['count']->total;?>
                <?php if ($_smarty_tpl->tpl_vars['page']->value!=$_smarty_tpl->tpl_vars['count']->value) {?>
                    <li><a class="comments-pagination-link" href="<?php echo $_smarty_tpl->tpl_vars['ajax_action_url']->value;?>
&configure=mymodcomments&ajax_hook=displayAdminProductsExtra&id_product=<?php echo $_GET['id_product'];?>
&page=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
"><span><?php echo $_smarty_tpl->tpl_vars['count']->value;?>
</span></a></li>
                <?php } else { ?>
                    <li class="active current"><span><span><?php echo $_smarty_tpl->tpl_vars['count']->value;?>
</span></span></li>
                <?php }?>
            <?php }} ?>
            </ul>
            <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['pc_base_dir']->value;?>
views/js/mymodcomments-backoffice-product.js"></script>
        <?php }?>

    </div>
</div>

<?php }} ?>
