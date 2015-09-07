<!-- < videodesk -->
<script type="text/javascript">
	var _videodesk= _videodesk || {ldelim}{rdelim};
	_videodesk['firstname'] = '{$customer_firstname}' ;
	_videodesk['lastname'] = '{$customer_lastname}' ;
	_videodesk['company'] = '{$customer_company}' ;
	_videodesk['email'] = '{$customer_email}' ;
	_videodesk['phone'] = '' ;
	_videodesk['customer_lang'] = '{$lang_iso}' ;
	_videodesk['customer_id'] = '{$customer_id}' ;
	_videodesk['customer_url'] = '' ;
	_videodesk['cart_id'] = '{$cart_id}' ;
	_videodesk['cart_url'] = '' ;
	_videodesk['uid'] = '{$website_id}' ;
	_videodesk['lang'] = '{$lang_iso}' ;
	_videodesk['display'] = '{$display}' ;
	_videodesk['module'] = 'prestashop' ;
	_videodesk['module_version'] = '{$module_version}' ;
	_videodesk['url'] = 'module-videodesk.com';

	(function() {ldelim}
		var videodesk = document.createElement('script'); videodesk.type =
		'text/javascript'; videodesk.async = true;
		videodesk.src = ('https:' == document.location.protocol ? 'https://' :
		'http://') + _videodesk['url'] + '/js/videodesk.js';
		var s = document.getElementsByTagName('script')[0];
	
		{if !empty($timeout)}
			if (document.cookie.indexOf("_videodesk_cuid") >= 0) {
				s.parentNode.insertBefore(videodesk, s);
			} else {
				setTimeout(function() {ldelim}
					s.parentNode.insertBefore(videodesk, s);
					{rdelim}, {$timeout}*1000);
			}

		{else}
			s.parentNode.insertBefore(videodesk, s);
		{/if}
	{rdelim})();
</script>
<!-- > videodesk -->