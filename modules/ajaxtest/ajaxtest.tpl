<div class="doAjax">Chiama</div>

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
        var url = "{$base_dir}modules/ajaxtest/ajax-test.php";
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
