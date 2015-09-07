<?php /* Smarty version Smarty-3.1.19, created on 2015-06-22 11:39:13
         compiled from "C:\xampp\htdocs\php\prestashop\modules\videodesk\\views\templates\admin\signup-step-1.tpl" */ ?>
<?php /*%%SmartyHeaderCode:39335587d7c1972867-92949757%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2fd4caa112faf843bae8d130de2d9fe2943b4233' => 
    array (
      0 => 'C:\\xampp\\htdocs\\php\\prestashop\\modules\\videodesk\\\\views\\templates\\admin\\signup-step-1.tpl',
      1 => 1434965903,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '39335587d7c1972867-92949757',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'lang_iso' => 0,
    'form_action' => 0,
    'module_path' => 0,
    'request_uri' => 0,
    'form' => 0,
    'errors' => 0,
    'error' => 0,
    'help' => 0,
    'module_help' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5587d7c19a52c4_32470207',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5587d7c19a52c4_32470207')) {function content_5587d7c19a52c4_32470207($_smarty_tpl) {?><script type="text/javascript">
    var lang_iso = "<?php echo $_smarty_tpl->tpl_vars['lang_iso']->value;?>
";
    var form_action = "<?php echo $_smarty_tpl->tpl_vars['form_action']->value;?>
";
    var module_path = "<?php echo $_smarty_tpl->tpl_vars['module_path']->value;?>
controllers/admin/AjaxSignUp.php";
    var module_uri = "<?php echo $_smarty_tpl->tpl_vars['request_uri']->value;?>
";

    function hideShops (first) {
        var i = 0;
        $('.shop h3').each(function () {
//             if (i == 0 && first == true) {
                $(this).parent().find('p').slideDown();
                $(this).addClass('active');
//             }
//             else {
//                 $(this).parent().find('p').slideUp();
//                 $(this).removeClass('active');
//             }
            i++;
        });
    }
    
    
    $(function() {
        $("#account_creation_form").dform({
             "action" : module_path,
            "html" : <?php echo $_smarty_tpl->tpl_vars['form']->value;?>

        });
        
        hideShops(true);
         $('.shop h3').on('click', function () {
            if ($(this).hasClass('active')) {
                hideShops(false);
                $(this).parent().find('p').slideUp();
                $(this).removeClass('active');
            }
            else {
                hideShops(false);
                $(this).parent().find('p').slideDown();
                $(this).addClass('active');
            }
        });
    });
    
</script>

<div id="ajax_wait"></div>

<div id="vd_content">

<div id="vd_sign_up_steps">
	<ul>
		<li class="active">1 <?php echo smartyTranslate(array('s'=>"Create account",'mod'=>"videodesk"),$_smarty_tpl);?>
 <span class="img"></span></li>
		<li>2 <?php echo smartyTranslate(array('s'=>"Pick users",'mod'=>"videodesk"),$_smarty_tpl);?>
</li>
		<li>3 <?php echo smartyTranslate(array('s'=>"Create users",'mod'=>"videodesk"),$_smarty_tpl);?>
</li>
	</ul>
</div>
<div style="clear: both;" />

<div id="errors" class="error">
<?php if (count($_smarty_tpl->tpl_vars['errors']->value)>0) {?>
	
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
	
<?php }?>
</div>

<div>
	<!-- < Sign Up -->
	<div id="vd_sign_up" class="step1">
		<h2><span class="step">1 </span>&nbsp;<?php echo smartyTranslate(array('s'=>'Create a videodesk account','mod'=>'videodesk'),$_smarty_tpl);?>
</h2>
		
			<form method="post" id="account_creation_form" novalidate="novalidate" >
				
			</form>
	</div>
	<!-- > Sign Up -->
	
	<!-- < Help -->
	<div id="vd_sign_up_help" class="step1">
		<iframe src="<?php echo $_smarty_tpl->tpl_vars['help']->value['src'];?>
" width="<?php echo $_smarty_tpl->tpl_vars['help']->value['width'];?>
px" height="<?php echo $_smarty_tpl->tpl_vars['help']->value['height'];?>
px" frameBorder="0"></iframe>
	</div>
	<!-- > Help -->
	
	
</div>

</div>

<script type="text/javascript">
	

 
	$(function() {                    
		$("#account_creation_form").on('submit', function() {
			$('#errors').hide();
			$('#ajax_wait').show();

			$.ajax({
				url: module_path,
				type: $(this).attr('method'),
				data: $(this).serialize(),
				dataType : "json",
				success: function(jsonData) {
					
					if (jsonData.hasError) {
						var errors = '';
						for(error in jsonData.errors)
							//IE6 bug fix
							if(error != 'indexOf')
								errors += '<li>'+jsonData.errors[error]+'</li>';
		 	    				$('#errors').html('<ul>'+errors+'</ul>');
		 	    				$('#errors').show();
			    					
								$('body,html').animate({
									scrollTop: 0
								}, 1000);
	                                            
					}
					else
					{
						document.location = $("<div/>").html(module_uri).text()+"&submitSignUp_Step1";
					}

	   			},
	   			error: function(XMLHttpRequest, textStatus, errorThrown)
	   			{
	   				alert("TECHNICAL ERROR: unable to load form.\n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
	   			}
			});	

			$('#ajax_wait').fadeOut("slow");
			
			return false;
		});
	});

	
</script>

<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['module_help']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('lang_iso'=>$_smarty_tpl->tpl_vars['lang_iso']->value), 0);?>
<?php }} ?>
