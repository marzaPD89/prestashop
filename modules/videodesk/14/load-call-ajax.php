<?php
/**
 * Load the transcript of a conversation
 */
include(dirname(__FILE__).'/../../../config/config.inc.php');
include(dirname(__FILE__).'/../../../init.php');
include(dirname(__FILE__).'/models/VideodeskCall.class.php');

$token = Tools::getValue('token');
if ($token === false || $token != Configuration::get('VD_ACCESS_TOKEN') || _PS_VERSION_ >= '1.5') {
	header('HTTP/1.1 401 Unauthorized ou Authorization required');
	exit;
}

$call = new VideodeskCall(Tools::getValue("id_call"));
echo $call->ajaxGetTranscription();