<?php
/**
 * Module videodesk - Ajax controller for Configuration
 *
 * @category   	Module / front_office_features
 * @author     	BM Services
 * @copyright  	2013 BM Services
 * @version   	2.1
 */
require_once(dirname(__FILE__).'/../../../../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../../../../init.php');
require_once(dirname(__FILE__).'/../../../videodesk.php');

$token = Tools::getValue('token');
if ($token === false || $token != Configuration::get('VD_ACCESS_TOKEN') || _PS_VERSION_ >= '1.5') {
	header('HTTP/1.1 401 Unauthorized ou Authorization required');
	exit;
}

$module = new Videodesk();

switch (Tools::getValue('action')) {
	
	// Create or update a Website Id, and save the configuration
	case 'createConfigurationShop':
		$id_shop = Tools::getValue('id_shop');
		if (!empty($id_shop)) {
			$merchant_code = Tools::getValue('merchant_code');
			
			$module = new Videodesk();
			$configuration = new VideodeskConfiguration($module);
			
			if ($configuration->checkWebsiteId($id_shop, $merchant_code)) {
				die(Tools::jsonEncode(array('hasError' => 0)));
			}
			else {
				die(Tools::jsonEncode(array(
					'hasError' => 1,
					'errors' => $configuration->getErrors()
				)));
			}
		} else {
			die(Tools::jsonEncode(array(
				'hasError' => 1,
				'errors' => array($module->l('ID shop is not defined'))
			)));
		}
		break;
	
	// Update the configuration progress / display of a shop
	case 'updateConfigurationShop':
		$computeProgress = false;
		$id_shop = Tools::getValue('id_shop');
		if (!empty($id_shop)) {
			$data = array();
			$website_id = Tools::getValue('website_id');
			$displayed = Tools::getValue('displayed', 3);
			$field = Tools::getValue('field');
			$value = Tools::getValue('value');
			if (!empty($website_id)) {
				$data['website_id'] = $website_id;
			}
			if ($displayed == 0 || $displayed == 1) {
				$data['displayed'] = $displayed;
			}
			if (!empty($field) && ($value == 0 || $value == 1)) {
				$data[$field] = $value;
				$progress = explode('progress_', $field);
				if (!empty($progress) && sizeof($progress) > 1) {
					$computeProgress = true;
				}
			}
			
			$result = Db::getInstance()->autoExecuteWithNullValues(_DB_PREFIX_.'videodesk_shop_configuration', $data, 'UPDATE', 'id_conf = ' . $id_shop);
			if ($result) {
				if ($computeProgress) {
					die(Tools::jsonEncode(array(
						'result' => VideodeskConfiguration::getProgress($id_shop)
					)));
				}
				die(Tools::jsonEncode(array(
					'result' => $module->l('Data has been updated')
				)));
			}
		}
		die(Tools::jsonEncode(array(
			'hasError' => 1,
			'errors' => array($module->l('ID shop is not defined'))
		)));
		break;
	default:
		exit;
}
exit;