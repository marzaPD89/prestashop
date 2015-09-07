<?php

include_once('./config/config.inc.php');

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'http://www.google.com');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml','Token: '. Configuration::get('GSYNC_TOKEN')));
curl_setopt($ch, CURLOPT_POSTFIELDS, 'prova');



$info = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

var_dump($info);

?>