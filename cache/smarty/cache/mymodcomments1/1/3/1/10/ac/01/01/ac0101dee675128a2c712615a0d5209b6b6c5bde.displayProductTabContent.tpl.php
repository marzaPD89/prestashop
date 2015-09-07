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
  'unifunc' => 'content_5555f16465a942_97468027',
  'cache_lifetime' => 31536000,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5555f16465a942_97468027')) {function content_5555f16465a942_97468027($_smarty_tpl) {?><h3 class="page-product-heading" id="mymodcomments-content-tab">Product Comments</h3>

<div class="rte">
</div>

<div class="rte">
    	<a href="http://localhost/php/prestashop/product-comments/list/tshirt-scolorite-manica-corta/1/page/1">See all comments</a>
</div>

<div class="rte">

    
	<form action="" method="POST" id="comment-form">

		<div class="form-group">
			<label for="firstname">Firstname:</label>
			<div class="row"><div class="col-xs-4">
                <input type=”text” name="firstname" id="firstname" class="form-control" />
            </div></div>
        </div>
		<div class="form-group">
            <label for="lastname">Lastname:</label>
			<div class="row"><div class="col-xs-4">
                <input type=”text” name="lastname" id="lastname" class="form-control" />
            </div></div>
        </div>
		<div class="form-group">
            <label for="email">Email:</label>
			<div class="row"><div class="col-xs-4">
				<input type=”email” name="email" id="email" class="form-control" />
			</div></div>
        </div>

                    <div class="form-group">
                <label for="grade">Grade:</label>
                <div class="row">
                    <div class="col-xs-4" id="grade-tab">
						<input id="grade" name="grade" value="0" type="number" class="rating" min="0" max="5" step="1" data-size="sm" >
				    </div>
			    </div>
		    </div>
                    		<div class="form-group">
	    		<label for="comment">Comment:</label>
		    	<textarea name="comment" id="comment" class="form-control"></textarea>
		    </div>
        		<div class="submit">
			<button type="submit" name="mymod_pc_submit_comment" class="button btn btn-default button-medium"><span>Send<i class="icon-chevron-right right"></i></span></button>
		</div>
	</form>
</div>
<?php }} ?>
