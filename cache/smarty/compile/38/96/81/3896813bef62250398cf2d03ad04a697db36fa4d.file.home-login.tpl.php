<?php /* Smarty version Smarty-3.1.19, created on 2015-06-22 12:20:12
         compiled from "C:\xampp\htdocs\php\prestashop\modules\videodesk\\views\templates\admin\home-login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:168135587e15c537b37-64521515%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3896813bef62250398cf2d03ad04a697db36fa4d' => 
    array (
      0 => 'C:\\xampp\\htdocs\\php\\prestashop\\modules\\videodesk\\\\views\\templates\\admin\\home-login.tpl',
      1 => 1434966700,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '168135587e15c537b37-64521515',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'bo_url' => 0,
    'edito' => 0,
    'errors' => 0,
    'error' => 0,
    'request_uri' => 0,
    'shops' => 0,
    'isMultiShop' => 0,
    'shop_group' => 0,
    'shop' => 0,
    'website_id' => 0,
    'account_exists' => 0,
    'img_dir' => 0,
    'pricing_url' => 0,
    'module_help' => 0,
    'lang_iso' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5587e15c5bac42_13323694',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5587e15c5bac42_13323694')) {function content_5587e15c5bac42_13323694($_smarty_tpl) {?><script type="text/javascript">
	var bo_url = '<?php echo $_smarty_tpl->tpl_vars['bo_url']->value;?>
';

    
    $(document).ready(function() {

        $("#vd_sign_in_button").live("click", function() {
            $("#vd_sign_in_button").slideUp();
            $("#vd_sign_in_form").slideDown();
        }); 
        
        $('span.infobulle').cluetip({splitTitle: '|'});
        
        $('#bo_redirect').click(function() {
        	var win = window.open(bo_url, '_blank', 'window settings');
			win.focus();
			return false;
		});

    });
    
</script>

<div id="vd_content">

<!-- < Edito -->
<div id="vd_edito">
    <iframe src="<?php echo $_smarty_tpl->tpl_vars['edito']->value['src'];?>
" width="<?php echo $_smarty_tpl->tpl_vars['edito']->value['width'];?>
px" height="<?php echo $_smarty_tpl->tpl_vars['edito']->value['height'];?>
px" frameBorder="0"></iframe>
</div>
<!-- > Edito -->

<?php if (count($_smarty_tpl->tpl_vars['errors']->value)>0) {?>
    <div class="error">
        <ul>
            <?php  $_smarty_tpl->tpl_vars['error'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['error']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['errors']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['error']->key => $_smarty_tpl->tpl_vars['error']->value) {
$_smarty_tpl->tpl_vars['error']->_loop = true;
?>
                <li><?php echo $_smarty_tpl->tpl_vars['error']->value;?>
</li>
            <?php } ?>
        </ul>
    </div>
<?php }?>

<div class="login clearfix">
    <!-- < Sign In -->
    <div id="vd_sign_in" class="block left clearfix">
        <h2><?php echo smartyTranslate(array('s'=>'Already a member? Sign in!','mod'=>'videodesk'),$_smarty_tpl);?>
</h2>
                
        <?php if (count($_smarty_tpl->tpl_vars['errors']->value)==0) {?>
            <span id="vd_sign_in_button" class="blueButton"><?php echo smartyTranslate(array('s'=>'Sign In','mod'=>'videodesk'),$_smarty_tpl);?>
</span>
        <?php }?>
        
        <div id="vd_sign_in_form" <?php if (count($_smarty_tpl->tpl_vars['errors']->value)==0) {?>style="display:none;"<?php }?>>
            <form action="<?php echo $_smarty_tpl->tpl_vars['request_uri']->value;?>
" method="post">
                <?php  $_smarty_tpl->tpl_vars['shop_group'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['shop_group']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['shops']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['shop_group']->key => $_smarty_tpl->tpl_vars['shop_group']->value) {
$_smarty_tpl->tpl_vars['shop_group']->_loop = true;
?>
                    <?php if ($_smarty_tpl->tpl_vars['isMultiShop']->value) {?>
                        <p class="title"><?php echo smartyTranslate(array('s'=>'Group:'),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['shop_group']->value['name'];?>
</p>
                    <?php }?>
                    <?php  $_smarty_tpl->tpl_vars['shop'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['shop']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['shop_group']->value['shops']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['shop']->key => $_smarty_tpl->tpl_vars['shop']->value) {
$_smarty_tpl->tpl_vars['shop']->_loop = true;
?>
                        <?php $_smarty_tpl->tpl_vars["index"] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['shop']->value['id_shop_group'])."_".((string)$_smarty_tpl->tpl_vars['shop']->value['id_shop']), null, 0);?>
                        <label><?php if ($_smarty_tpl->tpl_vars['isMultiShop']->value) {?><?php echo $_smarty_tpl->tpl_vars['shop']->value['name'];?>
 <?php } else { ?><?php echo smartyTranslate(array('s'=>"ID for your shop:",'mod'=>"videodesk"),$_smarty_tpl);?>
<?php }?></label>
                        <input type="text" name="website_id[<?php echo $_smarty_tpl->tpl_vars['shop']->value['id_shop_group'];?>
][<?php echo $_smarty_tpl->tpl_vars['shop']->value['id_shop'];?>
]" placeholder="<?php echo smartyTranslate(array('s'=>'Website ID','mod'=>'videodesk'),$_smarty_tpl);?>
" value="<?php if (isset($_smarty_tpl->tpl_vars['website_id']->value[$_smarty_tpl->tpl_vars['shop']->value['id_shop_group']][$_smarty_tpl->tpl_vars['shop']->value['id_shop']])) {?><?php echo $_smarty_tpl->tpl_vars['website_id']->value[$_smarty_tpl->tpl_vars['shop']->value['id_shop_group']][$_smarty_tpl->tpl_vars['shop']->value['id_shop']];?>
<?php }?>" style="width: 150px;"/>
                        <span class="infobulle" title="<?php echo smartyTranslate(array('s'=>'Home Help title','mod'=>'videodesk'),$_smarty_tpl);?>
|<?php echo smartyTranslate(array('s'=>'Home Help content','mod'=>'videodesk'),$_smarty_tpl);?>
"> ? </span>
                    <?php } ?>
                <?php } ?>
                <input type="submit" name="submitSignIn" value="<?php echo smartyTranslate(array('s'=>'Sign In','mod'=>'videodesk'),$_smarty_tpl);?>
" class="blueButton" style="float:left;" />
            </form>
        </div>
    </div>
    <!-- > Sign In -->
    
    <?php if ($_smarty_tpl->tpl_vars['account_exists']->value) {?>
    <!-- < Sign Up -->
    <div id="vd_sign_up_home" class="block right clearfix">
        <h2><?php echo smartyTranslate(array('s'=>'Visit your Videodesk Back Office','mod'=>'videodesk'),$_smarty_tpl);?>
</h2>
        
        <span id="bo_redirect" class="blueButton"><?php echo smartyTranslate(array('s'=>'Go','mod'=>'videodesk'),$_smarty_tpl);?>
</span>
    </div>
    <!-- > Sign Up -->
    <?php } else { ?>
    <!-- < Sign Up -->
    <div id="vd_sign_up_home" class="block right clearfix">
        <h2><?php echo smartyTranslate(array('s'=>'New to videodesk? Sign up!','mod'=>'videodesk'),$_smarty_tpl);?>
</h2>
        
        <form action="<?php echo $_smarty_tpl->tpl_vars['request_uri']->value;?>
" method="post">
            <input type="submit" name="submitSignUp" value="<?php echo smartyTranslate(array('s'=>'Sign Up','mod'=>'videodesk'),$_smarty_tpl);?>
" class="blueButton" />
        </form>
        
        <p class="spotted"><img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
spot.png" /> <?php echo smartyTranslate(array('s'=>'Free setup and Free to use','mod'=>'videodesk'),$_smarty_tpl);?>

        	<a href="<?php echo $_smarty_tpl->tpl_vars['pricing_url']->value;?>
" title="<?php echo smartyTranslate(array('s'=>'Read more','mod'=>'videodesk'),$_smarty_tpl);?>
" target="_blank" class="vd"><?php echo smartyTranslate(array('s'=>"Read more",'mod'=>"videodesk"),$_smarty_tpl);?>
</a>
        </p>
<!--         <p><?php echo smartyTranslate(array('s'=>"The videodesk module is free of charge, we don't charge any installation fee, videodesk is free to use up to 10 hours of discussion per month and per site.",'mod'=>'videodesk'),$_smarty_tpl);?>
</p> -->
    </div>
    <!-- > Sign Up -->
    <?php }?>
    
    <br class="clearBoth" />
</div>

</div>

<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['module_help']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('lang_iso'=>$_smarty_tpl->tpl_vars['lang_iso']->value), 0);?>
<?php }} ?>
