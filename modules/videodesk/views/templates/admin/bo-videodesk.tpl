<!-- < videodesk -->
<script type="text/javascript">
	var _videodesk= _videodesk || {ldelim}{rdelim};
	_videodesk['firstname'] = '{$employee_firstname}' ;
	_videodesk['lastname'] = '{$employee_lastname}' ;
	_videodesk['email'] = '{$employee_email}' ;
	_videodesk['lang'] = '{$lang_iso}' ;
	_videodesk['module'] = 'prestashop' ;
	_videodesk['module_version'] = '{$module_version}' ;
	_videodesk['url'] = '{$js_url}';

	(function() {ldelim}
		var videodesk = document.createElement('script'); videodesk.type =
		'text/javascript'; videodesk.async = true;
		videodesk.src = ('https:' == document.location.protocol ? 'https://' :
		'http://') + _videodesk['url'];
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(videodesk, s);
	{rdelim})();
</script>
<!-- > videodesk -->