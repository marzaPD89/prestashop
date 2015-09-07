<script type="text/javascript">
	var lang_iso = "{$lang_iso}";
	var form_action = "";
	var module_path = "{$module_path}14/controllers/admin/AjaxSignUp.php";
	var module_uri = "{$request_uri}";
	
    {literal}
	function hideShops (first) {
	    var i = 0;
	    $('.agent_shop h4').each(function () {
	        i++;
	    });
	}
	
	function hideAgents (first) {
	    var i = 0;
	    $('.agent_form h3').each(function () {
	        if (i == 0 && first == true) {
	            $(this).parent().find('p').slideDown();
	            $(this).addClass('active');
	        }
	        else {
	            $(this).parent().find('p').slideUp();
	            $(this).removeClass('active');
	        }
	        i++;
	    });
	}	
	
	function showNextAgent(id_employee) {
	    var i = 0;
	    $('.agent_form h3').each(function () {
	    	if (this.id == "agent_form_"+id_employee) {
	    		$(this).parent().find('p').slideDown();
	            $(this).addClass('active');
	    	}
	        else {
	            $(this).parent().find('p').slideUp();
	            $(this).removeClass('active');
	        }
	    });		
	}
	

    $(function() {
        $("#agent_creation_form").dform({
                "action" : module_path,
            "html" : {/literal}{$form}{literal}            
        });
		
        hideAgents(true);
        hideShops(true);
    });
    {/literal}

</script>

<div id="ajax_wait"></div>

<div id="vd_content">

<div id="vd_sign_up_steps">
	<ul>
		<li>1 {l s="Create account" mod="videodesk"}</li>
		<li>2 {l s="Pick users" mod="videodesk"}</li>
		<li class="active">3 {l s="Create users" mod="videodesk"}</li>
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
    
	<!--  step3 -->
    <div id="vd_sign_up">
            <h2><span class="step">3 </span>&nbsp;{l s='Create user accounts' mod='videodesk'}</h2>
        <div>
                <!-- < agent conf -->
                <form method="post" id="agent_creation_form" novalidate="novalidate" >

                </form>
                <!-- > agent conf -->
        </div>            
    </div>  
        
<script type="text/javascript">
	{literal}

	$(function() {                    
		$(".ui-dform-submit").on('click', function() {
                    $('#agent_creation_form').append("<input type='hidden' name='"+$(this).attr('name')+"' value='"+$(this).val()+"' />");  
                });
                
                $("#agent_creation_form").on('submit', function() {
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
						if (jsonData.action == "next") {
							showNextAgent(jsonData.id_employee);
						}
						else if (jsonData.action == "success") {
							document.location = $("<div/>").html(module_uri+"&submitSignUp_Step3").text();
						}
						else {
							return false;
						}
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