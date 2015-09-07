<?php /* Smarty version Smarty-3.1.19, created on 2015-05-15 15:15:16
         compiled from "C:\xampp\htdocs\php\prestashop\modules\mymodcomments\views\templates\hook\displayProductTabContent.tpl" */ ?>
<?php /*%%SmartyHeaderCode:106275555f1645a71c6-96737968%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ac0101dee675128a2c712615a0d5209b6b6c5bde' => 
    array (
      0 => 'C:\\xampp\\htdocs\\php\\prestashop\\modules\\mymodcomments\\views\\templates\\hook\\displayProductTabContent.tpl',
      1 => 1413766066,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '106275555f1645a71c6-96737968',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'new_comment_posted' => 0,
    'comments' => 0,
    'comment' => 0,
    'product' => 0,
    'params' => 0,
    'link' => 0,
    'enable_grades' => 0,
    'enable_comments' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5555f164634b77_12205295',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5555f164634b77_12205295')) {function content_5555f164634b77_12205295($_smarty_tpl) {?><h3 class="page-product-heading" id="mymodcomments-content-tab"<?php if (isset($_smarty_tpl->tpl_vars['new_comment_posted']->value)) {?> data-scroll="true"<?php }?>><?php echo smartyTranslate(array('s'=>'Product Comments','mod'=>'mymodcomments'),$_smarty_tpl);?>
</h3>

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

<div class="rte">
    <?php $_smarty_tpl->tpl_vars['params'] = new Smarty_variable(array('module_action'=>'list','product_rewrite'=>$_smarty_tpl->tpl_vars['product']->value->link_rewrite,'id_product'=>$_GET['id_product'],'page'=>1), null, 0);?>
	<a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getModuleLink('mymodcomments','comments',$_smarty_tpl->tpl_vars['params']->value);?>
"><?php echo smartyTranslate(array('s'=>'See all comments','mod'=>'mymodcomments'),$_smarty_tpl);?>
</a>
</div>

<?php if ($_smarty_tpl->tpl_vars['enable_grades']->value==1||$_smarty_tpl->tpl_vars['enable_comments']->value==1) {?>
<div class="rte">

    <?php if (isset($_smarty_tpl->tpl_vars['new_comment_posted']->value)&&$_smarty_tpl->tpl_vars['new_comment_posted']->value=='error') {?>
		<div class="alert alert-danger">
			<p><?php echo smartyTranslate(array('s'=>'Some fields of the form seems wrong, please check them before submitting your comment.','mod'=>'mymodcomments'),$_smarty_tpl);?>
</p>
		</div>
    <?php }?>

	<form action="" method="POST" id="comment-form">

		<div class="form-group">
			<label for="firstname"><?php echo smartyTranslate(array('s'=>'Firstname:','mod'=>'mymodcomments'),$_smarty_tpl);?>
</label>
			<div class="row"><div class="col-xs-4">
                <input type=”text” name="firstname" id="firstname" class="form-control" />
            </div></div>
        </div>
		<div class="form-group">
            <label for="lastname"><?php echo smartyTranslate(array('s'=>'Lastname:','mod'=>'mymodcomments'),$_smarty_tpl);?>
</label>
			<div class="row"><div class="col-xs-4">
                <input type=”text” name="lastname" id="lastname" class="form-control" />
            </div></div>
        </div>
		<div class="form-group">
            <label for="email"><?php echo smartyTranslate(array('s'=>'Email:','mod'=>'mymodcomments'),$_smarty_tpl);?>
</label>
			<div class="row"><div class="col-xs-4">
				<input type=”email” name="email" id="email" class="form-control" />
			</div></div>
        </div>

        <?php if ($_smarty_tpl->tpl_vars['enable_grades']->value==1) {?>
            <div class="form-group">
                <label for="grade"><?php echo smartyTranslate(array('s'=>'Grade:','mod'=>'mymodcomments'),$_smarty_tpl);?>
</label>
                <div class="row">
                    <div class="col-xs-4" id="grade-tab">
						<input id="grade" name="grade" value="0" type="number" class="rating" min="0" max="5" step="1" data-size="sm" >
				    </div>
			    </div>
		    </div>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['enable_comments']->value==1) {?>
    		<div class="form-group">
	    		<label for="comment"><?php echo smartyTranslate(array('s'=>'Comment:','mod'=>'mymodcomments'),$_smarty_tpl);?>
</label>
		    	<textarea name="comment" id="comment" class="form-control"></textarea>
		    </div>
        <?php }?>
		<div class="submit">
			<button type="submit" name="mymod_pc_submit_comment" class="button btn btn-default button-medium"><span><?php echo smartyTranslate(array('s'=>'Send','mod'=>'mymodcomments'),$_smarty_tpl);?>
<i class="icon-chevron-right right"></i></span></button>
		</div>
	</form>
</div>
<?php }?><?php }} ?>
