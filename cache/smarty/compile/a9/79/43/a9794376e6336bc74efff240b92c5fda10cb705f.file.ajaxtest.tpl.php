<?php /* Smarty version Smarty-3.1.19, created on 2015-05-15 15:15:17
         compiled from "C:\xampp\htdocs\php\prestashop\modules\ajaxtest\ajaxtest.tpl" */ ?>
<?php /*%%SmartyHeaderCode:212925555f1655b1711-20084858%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a9794376e6336bc74efff240b92c5fda10cb705f' => 
    array (
      0 => 'C:\\xampp\\htdocs\\php\\prestashop\\modules\\ajaxtest\\ajaxtest.tpl',
      1 => 1426517827,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '212925555f1655b1711-20084858',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'base_dir' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5555f1655c1d56_01633397',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5555f1655c1d56_01633397')) {function content_5555f1655c1d56_01633397($_smarty_tpl) {?><div class="doAjax">Chiama</div>

<script type="text/javascript">

    var localCache = {
        data: {},
        remove: function (url) {
            delete localCache.data[url];
        },
        exist: function (url) {
            return localCache.data.hasOwnProperty(url) && localCache.data[url] !== null;
        },
        get: function (url) {
            console.log('Getting in cache for url ' + url);
            return localCache.data[url];
        },
        set: function (url, cachedData, callback) {
            localCache.remove(url);
            localCache.data[url] = cachedData;
            if ($.isFunction(callback)) callback(cachedData);
        }
    };

    $('.doAjax').click(function() {
        if($('.list').length > 0)
            $('.list').remove();
        var url = "<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
modules/ajaxtest/ajax-test.php";
        $.ajax({
            url : url,
            type: "get",
            data: "action=true",
            cache: true,
            dataType: "html",
            success : function (data) {
                $('.doAjax').after(data);
            },
            error : function (stato) {
                alert("E' evvenuto un errore. Stato della chiamata: "+stato);
            },
            beforeSend: function () {
                console.log(localCache.exist(url));
                if (localCache.exist(url)) {
                    doSomething(localCache.get(url));
                    return false;
                }
                return true;
            },
            complete: function (jqXHR, textStatus) {
                if (!localCache.exist(url))
                    localCache.set(url, jqXHR);
            }
        });
    });

    function doSomething(data) {
        $('.doAjax').after(data.responseText);
    }

</script>
<?php }} ?>
