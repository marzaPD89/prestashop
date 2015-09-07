<?php
/**
 * Module videodesk - Bootstrap
 *
 * @category   	Module / front_office_features
 * @author     	BM Services
 * @copyright  	2013 BM Services
 * @version   	2.2
 */
// Security
if (!defined('_PS_VERSION_'))
	exit;

// Checking compatibility with older PrestaShop and fixing it
if (!defined('_MYSQL_ENGINE_'))
	define('_MYSQL_ENGINE_', 'MyISAM');

if (_PS_VERSION_ < '1.5') {
	// Loading Bootstrap for PrestaShop 1.4.X
	require_once(_PS_MODULE_DIR_ . 'videodesk/14/videodesk14.php');
	
	class Videodesk extends Videodesk14 {}
}
else {
	// Loading Bootstrap for PrestaShop 1.5.X
	require_once(_PS_MODULE_DIR_ . 'videodesk/videodesk15.php');
	
	class Videodesk extends Videodesk15 {}
}