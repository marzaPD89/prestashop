$(document).ready(function() {
    $('.vd_website_id_submit').on('click', function() {
        var id = 0;
        var merchant_code = 0;
        elements = $(this).attr('id').match('vd_website_id_submit_([0-9]*)');
        if (elements != undefined && elements != null && elements.length > 0 && elements[1] != undefined && elements[1] != null && elements[1] > 0) {
            id = elements[1];
        }
        if (id > 0) {
        	merchant_code = $('#vd_website_id_'+id).val();
        	token = $('#vd_token').val();
            if (merchant_code != null && merchant_code != undefined && merchant_code != '') {
                $.ajax({
                    type: 'get',
                    url: baseDir+'14/controllers/admin/AjaxConfiguration.php',
                    data: {
                        ajax: 1,
                        action: 'createConfigurationShop',
                        id_shop: id,
                        merchant_code: merchant_code,
                        token: token
                    },
                    dataType: 'json',
                    success: function(jsonData) {
                        if (jsonData.hasError)
                        {
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
                            $.get(module_uri, function(data) {
                               $("body").html(data);
                            });
                            
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown)
                    {
                        
                    }
                });
            }
        }
    });
    
    $('.vd_website_id_modify').on('click', function() {
        $(this).parent().parent().children('.modify').slideUp();
        $(this).parent().parent().children('.form').slideDown();
    });
    
    $('.vd_displayed_submit').on('click', function() {
        var id = 0;
        var displayed = 0;
        elements = $(this).attr('id').match('vd_displayed_submit_([0-9]*)');
        if (elements != undefined && elements != null && elements.length > 0 && elements[1] != undefined && elements[1] != null && elements[1] > 0) {
            id = elements[1];
        }
        if (id > 0) {
            if ($(this).hasClass('display')) {
                displayed = 1;
            }
            if (displayed != null && displayed != undefined) {
            	token = $('#vd_token').val();
                $.ajax({
                    type: 'get',
                    url: baseDir+'14/controllers/admin/AjaxConfiguration.php',
                    data: {
                        ajax: 1,
                        action: 'updateConfigurationShop',
                        id_shop: id,
                        displayed: displayed,
                        token: token
                    },
                    dataType: 'json',
                    success: function(jsonData) {
                        if (jsonData.hasError)
                        {
                            var errors = '';
                            for(error in jsonData.errors)
                                //IE6 bug fix
                                if(error != 'indexOf')
                                    errors += '<li>'+jsonData.errors[error]+'</li>';
                            $('#errors').html('<div class="alert alert-error" id="errors_content"><ul>'+errors+'</ul><div>');
                            $('#errors').show();
                            
                            $('body,html').animate({
                                scrollTop: 0
                            }, 1000);
                        }
                        else
                        {
                            $("#display"+id).slideUp(function(){});
                            $("#vd_admin"+id).slideDown(function(){});
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown)
                    {
                        
                    }
                });
            }
        }
    });
    
    $('.done').on('click', function() {
        var field = '';
        var id = 0;
        var value = 0;
        var currentButton = $(this);
        elements = $(this).attr('id').match('done_([a-z]*)_([0-9]*)');
        if (elements != undefined && elements != null && elements.length > 0) {
            if (elements[1] != undefined && elements[1] != null && elements[1] != '') {
                field = 'progress_'+elements[1];
            }
            if (elements[2] != undefined && elements[2] != null && elements[2] > 0) {
                id = elements[2];
            }
        }
        if (id > 0 && field != '') {
            if ($(this).hasClass('isDone')) {
                value = 1;
            }
            else {
                value = 0;
            }
            token = $('#vd_token').val();
            $.ajax({
                type: 'get',
                url: baseDir+'14/controllers/admin/AjaxConfiguration.php',
                data: {
                    ajax: 1,
                    action: 'updateConfigurationShop',
                    id_shop: id,
                    field: field,
                    value: value,
                    token: token
                },
                dataType: 'json',
                success: function(jsonData) {
                    if (jsonData.hasError)
                    {
                        var errors = '';
                        for(error in jsonData.errors)
                            //IE6 bug fix
                            if(error != 'indexOf')
                                errors += '<li>'+jsonData.errors[error]+'</li>';
                        $('#errors').html('<div class="alert alert-error" id="errors_content"><ul>'+errors+'</ul><div>');
                        $('#errors').show();
                        
                        $('body,html').animate({
                            scrollTop: 0
                        }, 1000);
                    }
                    else
                    {
                        if (currentButton.hasClass('isDone')) {
                                currentButton.parent().children('.isDone').slideUp('slow', function() {
                                //currentButton.parent().children('.noDone').slideDown('slow');                               
                                currentButton.parent().children('.blueButton').css('margin-right','127px');
                            });
                        }
//                        else {
//                            currentButton.parent().children('.noDone').slideUp('slow', function() {
//                                currentButton.parent().children('.isDone').slideDown('slow');
//                            });
//                        }
                        $('#progressBar_'+id+' .progressBar_number').text(jsonData.result);
                        progressBar(id);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown)
                {
                    
                }
            });
        }
    });
    
    $('.progressBar').each(function() {
        elements = $(this).attr('id').match('progressBar_([0-9]*)');
        if (elements != undefined && elements != null && elements.length > 0 && elements[1] != undefined && elements[1] != null && elements[1] > 0) {
            progressBar(elements[1]);
        }
    });
});

function progressBar (id_shop) {
    $('#progressBar_'+id_shop+' .myProgressBar').css('width', progressBarWidth);
    nbStepDone = nbStep * parseInt($('#progressBar_'+id_shop+' .progressBar_number').text()) / 100;
    $('#progressBar_'+id_shop+' .progress').css('width', progressBarWidth / nbStep * nbStepDone);
    $('#progressBar_'+id_shop+' .progress').css('display', 'block');
}