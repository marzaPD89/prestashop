<?php
/**
 * Module videodesk - Ajax controller for Sign Up
 *
 * @category   	Module / front_office_features
 * @author     	BM Services
 * @copyright  	2013 BM Services
 * @version   	2.1
 */
require_once(dirname(__FILE__) . '/../../../../config/config.inc.php');
require_once(dirname(__FILE__) . '/../../../../init.php');
require_once(dirname(__FILE__) . '/../../videodesk.php');
require_once(dirname(__FILE__) . '/../../lib/Unirest/Connector.php');

$token = Tools::getValue('token');
if ($token === false || $token != Configuration::getGlobalValue('VD_ACCESS_TOKEN')) {
	header('HTTP/1.1 401 Unauthorized ou Authorization required');
	exit;
}

$module = new Videodesk();
$default_error = $module->l('Failed to connect Videodesk server');

switch (Tools::getValue('signup_action')) {
	
	// Step 1
	case 'submitSignUp_Step1':
		$errors = array();
		
		$uid = Configuration::getGlobalValue('VD_ACCOUNT_UID');
		if ($uid == false) {
			// Account validation
			$data = array_merge(Tools::getValue('account'), array('conf' => Tools::getValue('conf')));
			try {
				$response = Unirest::post(
						Configuration::get('VD_FORM_SIGNUP_ACCOUNT_SUB'),
						array("Accept" => "application/json"),
						Tools::jsonEncode($data));
				
				if ($response->code == "200") {
					$content = $response->body;
					
					foreach ($content as $field) {
						if (isset($field->error_msg)) {
							$errors[] = $field->error_msg;
						}
					}
					
					if ((count($errors) > 0)) {
						die(Tools::jsonEncode(array(
							'hasError' => (count($errors) > 0) ? 1 : 0,
							'errors' => $errors,
							'content' => $content
						)));
					}
					else {
						$uid = $content->uid;
						Configuration::updateGlobalValue('VD_ACCOUNT_UID', $uid);
						$uid_agent = $content->uid_agent;
						Configuration::updateGlobalValue('VD_ACCOUNT_UID_AGENT', $uid_agent);
					}
				}
				else {
					die(Tools::jsonEncode(array(
						'hasError' => 1,
						'errors' => array($default_error)
					)));
				}
			}
			catch (Exception $ex) {
				die(Tools::jsonEncode(array(
					'hasError' => 1,
					'errors' => array($default_error)
				)));
			}
		}
		
		if ($uid !== false) {
			// Shops validation
			$websites = Tools::getValue('shop');
			foreach ($websites as &$website_fields) {
				if (isset($website_fields['logo']) && $website_fields['logo'] !== false) {
					$logoData = file_get_contents(_PS_IMG_DIR_ . $website_fields['logo']);
					$website_fields['logo_image'] = rawurlencode(base64_encode($logoData));
					$website_fields['logo_name'] = $website_fields['logo'];
				}
			}
			
			$data = array('uid' => $uid, 'websites' => $websites, 'conf' => Tools::getValue('conf'));
			try {
				$response = Unirest::post(
					Configuration::get('VD_FORM_SIGNUP_SHOP_SUB'),
					array("Accept" => "application/json"),
					Tools::jsonEncode($data));
				if ($response->code == "200") {
					$content = $response->body;
					
					if (isset($content->websites)) {
						foreach ($content->websites as $id_shop => $shop_fields) {
							foreach ($shop_fields as $field) {
								if (isset($field->error_msg)) {
									$errors[] = $field->error_msg;
								}
							}
						}
					}
					
					if ((count($errors) > 0)) {
						die(Tools::jsonEncode(array(
							'hasError' => (count($errors) > 0) ? 1 : 0,
							'errors' => $errors
						)));
					}
					
					// Store the Website configuration : uid and FTP authentication
					else {
						$website_configured = false;
						if (isset($content)) {
							foreach ($content as $id_shop => $shop) {
								if (is_object($shop)) {
									$vd_shop_configuration = new VideodeskShopConfiguration($id_shop);
									$vd_shop_configuration->id_shop = $id_shop;
									$vd_shop_configuration->website_id = $shop->uid;
									$vd_shop_configuration->display_for_all = true;
									$vd_shop_configuration->track_stats = true;
									$vd_shop_configuration->ftp_active = true;
									$vd_shop_configuration->ftp_host = $shop->ftp->host;
									$vd_shop_configuration->ftp_login = $shop->ftp->login;
									$vd_shop_configuration->ftp_password = $shop->ftp->password;
									$vd_shop_configuration->ftp_dir = $shop->ftp->dir;
									
									if (!$vd_shop_configuration->save()) {
										die(Tools::jsonEncode(array(
											'hasError' => 1,
											'errors' => array($module->l('Unable to store shop configuration'))
										)));
									}
									
									$website_configured = true;
								}
								elseif (strtolower($shop) == 'invalid key') {
									die(Tools::jsonEncode(array(
										'hasError' => 1,
										'errors' => array($module->l('Authentication problem with Videodesk server'))
									)));
								}
							}
						}
						
						if ($website_configured) {
							Configuration::updateGlobalValue('VD_CONF_STATE', 'configured');
							die(Tools::jsonEncode(array('hasError' => 0)));
						}
						else {
							die(Tools::jsonEncode(array(
								'hasError' => 1,
								'errors' => array($module->l('No shop was configured to be active on Videodesk'))
							)));
						}
					}
				}
				else {
					die(Tools::jsonEncode(array(
						'hasError' => 1,
						'errors' => array($default_error)
					)));
				}
			}
			catch (Exception $ex) {
				die(Tools::jsonEncode(array(
					'hasError' => 1,
					'errors' => array($default_error)
				)));
			}
		}
		
		die(Tools::jsonEncode(array(
			'hasError' => 1,
			'errors' => array($module->l('Error occured, please try again'))
		)));
		break;
	
	// Step 3	
	case 'submitSignUp_Step3':
		$errors = array();
		
		$uid_agent = Configuration::getGlobalValue('VD_ACCOUNT_UID_AGENT');
		
		$datas = Tools::getValue('agent');
		foreach ($datas as $id_employee => $data) {
			if (isset($data['submitSignUp_Step3'])) {
				$agent = $data;
				$id_current_employee = $id_employee;
			}
		}
		
		// Agent validation
		if ($uid_agent === false || $uid_agent == null) {
			
			$data = $agent['agent_account'];
			$posted_data = array_merge($data, array(
				'conf' => Tools::getValue('conf')
			));
			
			try {
				$response = Unirest::post(
					Configuration::get('VD_FORM_SIGNUP_AGENT_SUB'),
					array("Accept" => "application/json"),
					Tools::jsonEncode($posted_data));
				if ($response->code == "200") {
					$content = $response->body;
					
					foreach ($content as $field) {
						if (isset($field->error_msg)) {
							$errors[] = $field->error_msg;
						}
					}
					
					if ((count($errors) > 0)) {
						die(Tools::jsonEncode(array(
							'hasError' => (count($errors) > 0) ? 1 : 0,
							'errors' => $errors,
							'content' => $content
						)));
					}
					else {
						$uid_agent = $content->uid_agent;
						Configuration::updateGlobalValue('VD_ACCOUNT_UID_AGENT', $uid_agent);
					}
				}
				else {
					die(Tools::jsonEncode(array(
						'hasError' => 1,
						'errors' => array($default_error)
					)));
				}
			}
			catch (Exception $ex) {
				die(Tools::jsonEncode(array(
					'hasError' => 1,
					'errors' => array($default_error)
				)));
			}
		}
		
		// Shops validation
		if ($uid_agent !== false) {
			$agent_shops = $agent['shop'];
			$agent_email = $agent['agent_account']['email'];
			
			foreach ($agent_shops as $id_shop => $agent_shop) {
				$shoperrors = array();
				$shop = new Shop(str_replace("shop", "", $id_shop));
				
				$agent_shop["uid_site"] = VideodeskShopConfiguration::getWebsite_idByIdShops($shop->id);
				$agent_shop["uid_agent"] = $uid_agent;
				
				$posted_data = array_merge($agent_shop, array('conf' => Tools::getValue('conf')));
				$response = Unirest::post(
					Configuration::get('VD_FORM_SIGNUP_SHOP_AGENT_SUB'),
					array("Accept" => "application/json"),
					Tools::jsonEncode($posted_data));
				if ($response->code == "200") {
					$content = $response->body;
					
					foreach ($content as $field) {
						if (isset($field->error_msg)) {
							$errors[] = "" . $shop->name . " : " . $field->error_msg;
						}
						
						if (isset($field->fields)) {
							foreach ($field->fields as $fi) {
								if (isset($fi->error_msg)) {
									$errors[] = "" . $shop->name . " : " . $fi->error_msg;
								}
							}
						}
					}
					
					if ((count($errors) > 0)) {
						$shoperrors = Tools::jsonEncode(array(
							'hasError' => (count($errors) > 0) ? 1 : 0,
							'errors' => $errors,
							'content' => $content
						));
					}
				}
				else {
					$shoperrors = Tools::jsonEncode(array(
						'shop name' => $shop->name,
						'hasError' => 1,
						'errors' => array($default_error)
					));
				}
			}
			if (!empty($shoperrors)) {
				die(($shoperrors));
			}
			else {
				// Delete agent for other agent submit
				Configuration::updateGlobalValue('VD_ACCOUNT_UID_AGENT', null);
				
				$id_last_employee = end(array_keys($datas));
				if ($id_current_employee == $id_last_employee) {
					$action = array(
						'action' => 'success'
					);
				}
				else {
					$found = false;
					reset($datas);
					foreach ($datas as $id_employee => $data) {
						if ($found) {
							$action = array(
								'action' => 'next',
								'id_employee' => $id_employee
							);
							break;
						}
						if ($id_current_employee == $id_employee) {
							$found = true;
						}
					}
				}
				
				die(Tools::jsonEncode(array_merge(array(
					'hasError' => 0,
					'content' => $content
				), $action)));
			}
		}
	
	default:
		die(Tools::jsonEncode(array(
			'hasError' => 1,
			'errors' => array($module->l('Action not recognized'))
		)));
		exit;
}
exit;