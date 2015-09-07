<script type="text/javascript">
    var lang_iso = "{$lang_iso}";
    var form_action = "";
    var module_path = "{$module_path}14/controllers/admin/AjaxSignUp.php";
    var module_uri = "{$request_uri}";

    {literal}
    function hideShops (first) {
        var i = 0;
        $('.shop h3').each(function () {
                $(this).parent().find('p').slideDown();
                $(this).addClass('active');
            i++;
        });
    }
    
    $(function() {
        $("#account_creation_form").dform({
             "action" : module_path,
            "html" : {/literal}{$form}{literal}
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
    {/literal}
</script>

<div id="ajax_wait"></div>

<div id="vd_content">

<div id="vd_sign_up_steps">
	<ul>
		<li class="active">1 {l s="Create account" mod="videodesk"} <span class="img"></span></li>
		<li>2 {l s="Pick users" mod="videodesk"}</li>
		<li>3 {l s="Create users" mod="videodesk"}</li>
	</ul>
</div>
<div style="clear: both;" />

<div id="errors" class="error">
{if $errors|@count > 0}
	
		<ul>
			{foreach from=$errors item=error}
				<li>{$error}</li>
			{/foreach}
		</ul>
	
{/if}
</div>

<div>
	<!-- < Sign Up -->
	<div id="vd_sign_up" class="step1">
		<h2><span class="step">1 </span>&nbsp;{l s='Create a videodesk account' mod='videodesk'}</h2>
		
			<form method="post" id="account_creation_form" novalidate="novalidate" >
				
			</form>
	</div>
	<!-- > Sign Up -->
	
	<!-- < Help -->
	<div id="vd_sign_up_help" class="step1">
		<iframe src="{$help.src}" width="{$help.width}px" height="{$help.height}px" frameBorder="0"></iframe>
	</div>
	<!-- > Help -->
	
	
</div>

</div>

<script type="text/javascript">
	{literal}

 
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

	{/literal}
</script>

{include file=$module_help lang_iso=$lang_iso}