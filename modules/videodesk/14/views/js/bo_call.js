function loaddetail(id){
	stopAjaxQuery();
	token = $('#vd_token').val();
	ajaxQuery = $.ajax({
		type: 'GET',
		url: baseDir + '14/load-call-ajax.php',
		data:{id_call : id, token: token},
		dataType: 'html',
		cache: false,
		success: function(result) {
			$("#call_detail #call_content").html(result);
		}
	});
	ajaxQueries.push(ajaxQuery);
}

function stopAjaxQuery() {
	if (typeof(ajaxQueries) == 'undefined')
		ajaxQueries = new Array();
	for(i = 0; i < ajaxQueries.length; i++)
		ajaxQueries[i].abort();
	ajaxQueries = new Array();
}

$(document).ready(function(){
	var html=$("#call_detail #call_content").html(); //stock l'image du loader pour réinsertion

	$(".detail").click(function(){
		$("#call_bg").fadeIn(function(){});
		$("#call_detail").fadeIn(function(){});
		loaddetail($(this).attr('id'));
	});

	$("#close_call").click(function(){
		$("#call_bg").fadeOut(function(){});
		$("#call_detail").fadeOut(function(){});
		$("#call_detail #call_content").html(html);
	});
});