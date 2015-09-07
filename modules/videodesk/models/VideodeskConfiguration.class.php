<?php
/**
 * Module videodesk - Action controller for module configure displays and processes
 *
 * @category   	Module / front_office_features
 * @author     	BM Services
 * @copyright  	2013 BM Services
 * @version   	2.1
 */

// Loading Models
require_once(_PS_MODULE_DIR_ . 'videodesk/models/VideodeskShopConfiguration.class.php');

// Loading Libs
require_once(_PS_MODULE_DIR_ . 'videodesk/lib/Unirest/Connector.php');
require_once(_PS_MODULE_DIR_ . 'videodesk/lib/Http/Connector.php');

class VideodeskConfiguration
{
	private $context;
	private $module;
	private $_template;
	private $_display;
	private $_errors;
	private $_need_form;
	private static $_nbStep = 5;
	private static $_progressBarWidth = 380;
	
	public function __construct($module = null)
	{
		$this->context = Context::getContext();
		$this->module = $module;
		$this->_display = false;
		$this->_need_form = false;
		$this->_errors = array();
	}
	
	/**
	 * Display for 1st access to module configuration
	 */
	public function processDisplayHome()
	{
		$this->_display = true;
		$isMultiShop = $this->isMultiShops();
		
		// First connection or no Website ID set => Sign In / Sign Up page
		$this->_template = 'home-login';
		
		$account_exists = $this->videodeskEmailExist($this->context->employee->email);
		
		$edito = array(
			"src" => $this->buildUrl(Configuration::get('VD_EDITO_URL')),
			"width" => Configuration::get('VD_EDITO_WIDTH'),
			"height" => Configuration::get('VD_EDITO_HEIGHT')
		);
		
		$this->context->smarty->assign(array(
			'isMultiShop' => $isMultiShop,
			'shops' => $this->getShops(),
			'edito' => $edito,
			'account_exists' => $account_exists,
			'pricing_url' => $this->buildUrl(Configuration::get('VD_PRICING')),
			// 'bo_url' => $this->buildUrl(Configuration::get('VD_BO_HOME')),
			'bo_url' => Configuration::get('VD_BO_HOME'),
			'img_dir' => $this->module->getPathUri() . 'views/img/'
		));
	}
	
	/**
	 * Display Standard once at least a shop is configured
	 */
	public function processDisplayConfiguration()
	{
		$this->_display = true;
		$isMultiShop = $this->isMultiShops();
		
		// Website IDs have been set => Configuration page
		$this->_template = 'home';
		
		$shop_context = 'shop';
		if ($isMultiShop) {
			if (Shop::getContext() == Shop::CONTEXT_SHOP) {
				$shop_context = 'shop';
			}
			elseif (Shop::getContext() == Shop::CONTEXT_GROUP) {
				$shop_context = 'group';
			}
			else {
				$shop_context = 'all';
			}
		}
		
		$edito = array(
			"src" => $this->buildUrl(Configuration::get('VD_EDITO_URL')),
			"width" => Configuration::get('VD_EDITO_WIDTH'),
			"height" => Configuration::get('VD_EDITO_HEIGHT')
		);
		
		$this->context->controller->addJS($this->module->getPathUri() . 'views/js/configuration.js');
		
		$this->context->smarty->assign(array(
			'token_videodesk' => Tools::getAdminTokenLite('AdminModules', $this->context->getContext()),
			'token' => Configuration::getGlobalValue('VD_ACCESS_TOKEN'),
			'baseDir' => $this->module->getPathUri(),
			'img_dir' => $this->module->getPathUri() . 'views/img/',
			'lang_iso' => $this->context->language->iso_code,
			'isMultiShop' => $isMultiShop,
			'shops' => $this->getShops($shop_context),
			'edito' => $edito,
			'shopContext' => $this->context->cookie->__get('shopContext'),
			'VD_BO_CONF' => $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->module->name . '&tab_module=' . $this->module->tab . '&module_name=' . $this->module->name,
			// 'VD_BO_HOME' => $this->buildUrl(Configuration::get('VD_BO_HOME')),
			'VD_BO_HOME' => Configuration::get('VD_BO_HOME'),
			'VD_BO_TEMPLATE' => Configuration::get('VD_BO_TEMPLATE'),
			'VD_BO_TEXTS' => Configuration::get('VD_BO_TEXTS'),
			'VD_BO_MESSAGES' => Configuration::get('VD_BO_MESSAGES'),
			'VD_BO_AGENT' => Configuration::get('VD_BO_AGENT'),
			'VD_PREVIEW_URL' => Configuration::get('VD_PREVIEW_URL'),
			'URL_SUFFIX' => $this->buildUrl(''),
			'nbStep' => self::$_nbStep,
			'progressBarWidth' => self::$_progressBarWidth
		));
	}
	
	/**
	 * Display Advanced Configuration for a shop (criterias, scope...)
	 */
	public function processDisplayShopConfiguration()
	{
		$id_shop = (int) Tools::getValue('id_shop');
		$shopContext = Tools::getValue('old_url');
		if (empty($id_shop)) {
			$this->processDisplayConfiguration();
			return;
		}
		if (Shop::isFeatureActive() && ($this->context->shop->getContext() != Shop::CONTEXT_SHOP || $this->context->shop->getContextShopID() != $id_shop)) {
			$this->context->cookie->__set('shopContext', 's-' . $id_shop);
			$this->context->controller->initShopContext();
		}
		
		$this->_display = true;
		
		// Website IDs have been set => Configuration page
		$this->_template = 'shop-configuration';
		
		$edito = array(
			"src" => $this->buildUrl(Configuration::get('VD_EDITO_URL')),
			"width" => Configuration::get('VD_EDITO_WIDTH'),
			"height" => Configuration::get('VD_EDITO_HEIGHT')
		);
		
		$this->context->controller->addJS($this->module->getPathUri() . 'views/js/shop-configuration.js');
		
		//get all groups of pages with pages
		$groups_pages = VideodeskConfiguration::getGroupsPages($id_shop);
		//get modules controllers
		$modules = VideodeskConfiguration::getModulesControllers();
		if (!empty($modules)) {
			$group_module = VideodeskShopConfigurationGroupPages::getByName('modules');
			$group_module['pages'] = $modules;
		}
		foreach ($groups_pages as $key => $group) {
			if (!empty($group_module['id_group_pages'])) {
				if ($group['id_group_pages'] == $group_module['id_group_pages']) {
					$groups_pages[$key] = $group_module;
				}
			}
			else if ($group['name'] == 'modules') {
				unset($groups_pages[$key]);
			}
		}
		//get categories
		$categories = $this->getCategoriesTree();
		if (!empty($categories)) {
			$group_module = VideodeskShopConfigurationGroupPages::getByName('categories');
			$group_module['pages'] = $categories;
		}
		foreach ($groups_pages as $key => $group) {
			if (!empty($group_module['id_group_pages'])) {
				if ($group['id_group_pages'] == $group_module['id_group_pages']) {
					$groups_pages[$key] = $group_module;
				}
			}
			else if ($group['name'] == 'categories') {
				unset($groups_pages[$key]);
			}
		}
		//get CMS
		$cmsPages = CMS::getCMSPages($this->context->language->id);
		if (!empty($cmsPages)) {
			$group_module = VideodeskShopConfigurationGroupPages::getByName('cms');
			foreach ($cmsPages as $key => $cms) {
				$page = VideodeskShopConfigurationPageValue::getActiveValue('cms', $id_shop, $cms['id_cms']);
				$cmsPages[$key]['active'] = $page['active'];
			}
			$group_module['pages'] = $cmsPages;
		}
		foreach ($groups_pages as $key => $group) {
			if (!empty($group_module['id_group_pages'])) {
				if ($group['id_group_pages'] == $group_module['id_group_pages']) {
					$groups_pages[$key] = $group_module;
				}
			}
			else if ($group['name'] == 'cms') {
				unset($groups_pages[$key]);
			}
		}
		
		$default_currency = Currency::getDefaultCurrency();
		
		$this->context->smarty->assign(array(
			'token_videodesk' => Tools::getAdminTokenLite('AdminModules', $this->context->getContext()),
			'path_module' => _PS_MODULE_DIR_ . $this->module->name . '/views/templates/admin/',
			'baseDir' => $this->module->getPathUri(),
			'img_dir' => $this->module->getPathUri() . 'views/img/',
			'id_shop' => $id_shop,
			'shops' => $this->getShops('shop'),
			'edito' => $edito,
			'currencySign' => $default_currency->sign,
			'old_shop_context' => $shopContext,
			'return_url' => $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->module->name . '&tab_module=' . $this->module->tab . '&module_name=' . $this->module->name . '&shopContext=' . Tools::getValue('old_url'),
			'criterias' => VideodeskConfiguration::getCriterias($id_shop),
			'groups_pages' => $groups_pages
		));
	}
	
	/**
	 * Processes Update configuration of a shop
	 */
	public function processUpdateShopConfiguration()
	{
		$id_shop = Tools::getValue('id_shop');
		if (!empty($id_shop)) {
			//save criterias
			$shop_conf = new VideodeskShopConfiguration($id_shop);
			$shop_conf->displayed = Tools::getValue('displayed');
			$shop_conf->display_for_all = Tools::getValue('display_for_all');
			$shop_conf->display_ips = Tools::getValue('display_ips');
			$shop_conf->progress_criterias = 1;
			$shop_conf->criterias = Tools::getValue('criterias');
			$shop_conf->criterias_all_conditions = Tools::getValue('criterias_all_conditions');
			$shop_conf->scope = Tools::getValue('scope');
			$shop_conf->track_stats = Tools::getValue('track_stats');
			$shop_conf->save();
			
			// IP validation
			if ($shop_conf->display_for_all == 0) {
				if (empty($shop_conf->display_ips)) {
					$this->_errors[] = $this->module->l('You have to fill IP addresses');
				}
			}
			
			$need_validation_criterias = ($shop_conf->criterias == 1) ? true : false;
			if ($need_validation_criterias) {
				$has_filled_criterias = false;
				$need_validation_criterias_type = ($shop_conf->criterias_all_conditions == 0) ? 'one' : 'all';
			}
			$criterias = Tools::getValue('criteria', array());
			$criterias_value = Tools::getValue('criteria_value');
			VideodeskShopConfigurationCriteriaValue::EnableAllForShop($id_shop);
			foreach ($criterias as $key => $value) {
				$criteria = new VideodeskShopConfigurationCriteria($key);
				$criteria_value = new VideodeskShopConfigurationCriteriaValue($key, $this->context->language->id, $id_shop);
				$criteria_value->id_shop = $id_shop;
				$criteria_value->id_criteria = $key;
				$criteria_value->active = ($need_validation_criterias) ? $value : false;
				if ($criteria->with_value == 1) {
					$criteria_value->value = $criterias_value[$key];
				}
				$criteria_value->save();
				
				// Verify that the criteria has been checked, and validate the values
				// if ($need_validation_criterias) {
				if ($value == 1) {
					$has_filled_criterias = true;
				}
				
				if ($criteria->with_value == 1) {
					$valid = true;
					if (empty($criteria_value->value)) {
						$valid = false;
						$this->_errors[] = $this->module->l("criteria_" . $key . ":") . " " . $this->module->l('You have to fill a value for this criteria');
					}
					else {
						// Seconds, number of visits
						if ($key == 1 || $key == 5) {
							if (!Validate::isFloat($criteria_value->value)) {
								$valid = false;
								$this->_errors[] = $this->module->l("criteria_" . $key . ":") . " " . $this->module->l('Invalid format');
							}
						}
						elseif ($key == 2 || $key == 3) {
							if (!Validate::isPrice($criteria_value->value)) {
								$valid = false;
								$this->_errors[] = $this->module->l("criteria_" . $key . ":") . " " . $this->module->l('Invalid format');
							}
						}
					}
					
					if (!$valid) {
						$criteria_value->active = $valid;
						$criteria_value->save();
					}
				}
			}
			
			// If need criterias & no criteria selected => error
			if ($need_validation_criterias) {
				if (!$has_filled_criterias) {
					$this->_errors[] = $this->module->l('You have to select at least one criteria');
				}
			}
			
			//delete all links with pages
			VideodeskShopConfigurationPageValue::deleteAllForShop($id_shop);
			
			$need_validation_pages = ($shop_conf->scope == 1) ? true : false;
			if ($need_validation_pages) {
				$has_filled_pages = false;
			}
			
			//save categories
			$categories = Tools::getValue('categories');
			$group = VideodeskShopConfigurationGroupPages::getByName('categories');
			if (!empty($categories)) {
				foreach ($categories as $id_category => $value) {
					$id_page = (int) VideodeskShopConfigurationPage::getPageId($group['id_group_pages'], $id_category);
					$page = new VideodeskShopConfigurationPage($id_page);
					if (empty($page->id)) {
						$page->id_group_pages = (int) $group['id_group_pages'];
						$page->name = (int) $id_category;
						$page->save();
					}
					
					$page_value = new VideodeskShopConfigurationPageValue();
					$page_value->id_page = (int) $page->id;
					$page_value->id_shop = $id_shop;
					$page_value->save();
				}
				$has_filled_pages = true;
			}
			
			//save cms
			$cms = Tools::getValue('cms');
			$group = VideodeskShopConfigurationGroupPages::getByName('cms');
			if (!empty($cms)) {
				foreach ($cms as $id_cms => $value) {
					$id_page = (int) VideodeskShopConfigurationPage::getPageId($group['id_group_pages'], $id_cms);
					$page = new VideodeskShopConfigurationPage($id_page);
					if (empty($page->id)) {
						$page->id_group_pages = (int) $group['id_group_pages'];
						$page->name = (int) $id_cms;
						$page->save();
					}
					
					$page_value = new VideodeskShopConfigurationPageValue();
					$page_value->id_page = (int) $page->id;
					$page_value->id_shop = $id_shop;
					$page_value->save();
				}
				$has_filled_pages = true;
			}
			
			//save modules
			$modules = Tools::getValue('modules');
			$group = VideodeskShopConfigurationGroupPages::getByName('modules');
			if (!empty($modules)) {
				foreach ($modules as $id_module => $value) {
					$id_page = VideodeskShopConfigurationPage::getPageId($group['id_group_pages'], $id_module);
					$page = new VideodeskShopConfigurationPage($id_page);
					if (empty($page->id)) {
						$page->id_group_pages = (int) $group['id_group_pages'];
						$page->name = $id_module;
						$page->save();
					}
					
					$page_value = new VideodeskShopConfigurationPageValue();
					$page_value->id_page = (int) $page->id;
					$page_value->id_shop = $id_shop;
					$page_value->save();
				}
				$has_filled_pages = true;
			}
			
			//save pages
			$pages = Tools::getValue('pages');
			$groups = VideodeskShopConfigurationGroupPages::getAllGroups();
			if (!empty($pages) && !empty($groups)) {
				foreach ($groups as $key => $group) {
					if ($group['name'] == 'categories' || $group['name'] == 'modules' || $group['name'] == 'cms') {
						unset($groups[$key]);
					}
				}
				foreach ($pages as $key => $value) {
					$page = VideodeskShopConfigurationPage::getByName($key);
					if ($value == 1) {
						$page_value = new VideodeskShopConfigurationPageValue();
						$page_value->id_page = (int) $page['id_page'];
						$page_value->id_shop = $id_shop;
						$page_value->save();
					}
				}
				$has_filled_pages = true;
			}
			
			// If need pages & no page selected => error
			if ($need_validation_pages) {
				if (!$has_filled_pages) {
					$this->_errors[] = $this->module->l('You have to select at least one page');
				}
			}
		}
		
		// Redirection after process
		if (count($this->_errors) > 0) {
			$this->processDisplayShopConfiguration();
		}
		else {
			$this->processDisplayConfiguration();
		}
	}
	
	/**
	 * Processes Validation of a Website ID
	 */
	public function processSignIn()
	{
		$website_ids = Tools::getValue('website_id');
		$website_configured = false;
		
		foreach ($website_ids as $id_shop_group => $shop_values) {
			foreach ($shop_values as $id_shop => $merchant_code) {
				$validation = $this->checkWebsiteId($id_shop, $merchant_code);
				if ($validation) {
					$website_configured = true;
				}
			}
		}
		
		if ($website_configured) {
			Configuration::updateGlobalValue('VD_CONF_STATE', 'configured');
		}
		else {
			if (count($this->_errors) == 0) {
				$this->_errors[] = $this->module->l('No Website ID could be validated');
			}
		}
		
		$this->context->smarty->assign('website_id', Tools::getValue('website_id'));
		
		if (count($this->_errors) > 0)
			return false;
		else
			return true;
	}
	
	/**
	 * Check Website Id upon Videodesk
	 * 
	 * @param int $id_shop
	 * @param string $website_id
	 * @return boolean
	 */
	public function checkWebsiteId($id_shop, $website_id)
	{
		$website_id = trim($website_id);
		if ($website_id != '') {
			// Website ID validation - If Secured connection => cURL / Else => HTTP POST
			$posted_data = array_merge($this->getConfPost(), array('website_id' => $website_id));
			if (Configuration::getGlobalValue('VD_CONNECTION_SECURED') == '1') {
				$response = Unirest::post(
					$this->buildUrl(Configuration::get('VD_FORM_SIGNIN')),
					array("Accept" => "application/json"),
					Tools::jsonEncode($posted_data));
				if ($response->code == "200") {
					$content = $response->body;
					
					return $this->validateWebsiteId($id_shop, $content, $website_id);
				}
				else {
					$this->_errors[] = $this->module->l('Unable to validate Website ID. Please verify yout Internet connection or contact videodesk support');
				}
			}
			else {
				$response = HttpRequester::post(
					$this->buildUrl(Configuration::get('VD_FORM_SIGNIN')), $posted_data);
				if ($response->getStatus() == "200") {
					$content = $response->asJson();
					
					return $this->validateWebsiteId($id_shop, $content, $website_id);
				}
				else {
					$this->_errors[] = $this->module->l('Unable to validate Website ID. Please verify yout Internet connection or contact videodesk support');
				}
			}
		}
		
		return false;
	}
	
	/**
	 * Validate the response of a merchant code (Website Id - Key) check and create Shop configuration
	 * 
	 * @param int $id_shop
	 * @param JSON $content
	 * @param string $merchant_code
	 * @return boolean
	 */
	public function validateWebsiteId($id_shop, $content, $merchant_code)
	{
		if (isset($content->valid) && $content->valid) {
			$pos = strpos($merchant_code, '-');
			if ($pos !== false) {
				list($website_id, $key) = explode('-', $merchant_code);
				$vd_shop_configuration = new VideodeskShopConfiguration();
				$vd_shop_configuration->id_shop = $id_shop;
				$vd_shop_configuration->website_id = $website_id;
				$vd_shop_configuration->display_for_all = true;
				$vd_shop_configuration->track_stats = true;
	
				if (Configuration::getGlobalValue('VD_CONNECTION_SECURED') == '1') {
					$vd_shop_configuration->ftp_active = true;
					$vd_shop_configuration->ftp_host = $content->ftp->host;
					$vd_shop_configuration->ftp_login = $content->ftp->login;
					$vd_shop_configuration->ftp_password = $content->ftp->password;
					$vd_shop_configuration->ftp_dir = $content->ftp->dir;
				}
	
				if ($vd_shop_configuration->save()) {
					return true;
				}
				else {
					$this->_errors[] = $this->module->l('Error in creating the configuration for the Website ID:') . ' <strong>' . $merchant_code . '</strong>';
				}
			}
			else {
				$this->_errors[] = $this->module->l('Format of Website ID not recognized:') . ' <strong>' . $merchant_code . '</strong>';
			}
		}
		else {
			$this->_errors[] = $this->module->l('This Website ID is invalid:') . ' <strong>' . $merchant_code . '</strong>';
		}
	
		return false;
	}

	/**
	 * Check if an email already exists as a Videodesk account
	 * 
	 * @param string $email
	 * @return boolean
	 */
	public function videodeskEmailExist($email)
	{
		$email = trim($email);
		if ($email != '') {
			$posted_data = array_merge($this->getConfPost(), array('email' => $email));
			$response = HttpRequester::post(
				Configuration::get('VD_EMAIL_CHECK'),
				$posted_data);
			if ($response->getStatus() == "200") {
				$content = $response->asJson();
				
				if ($content->exist)
					return true;
				else
					return false;
			}
			else {
				$this->_errors[] = $this->module->l('Unable to validate Email. Please verify yout Internet connection or contact videodesk support');
				break;
			}
		}
		
		return false;
	}
	
	/**
	 * Display Sign Up page - Step 1
	 */
	public function processDisplaySignUpStep1()
	{
		$this->_display = true;
		$this->_need_form = true;
		$this->_template = 'signup-step-1';
		
		$isMultiShop = $this->isMultiShops();
		
		// Get Account fields
		$account_fields = null;
		try {
			$response = HttpRequester::post(
				$this->buildUrl(Configuration::getGlobalValue('VD_FORM_SIGNUP_ACCOUNT')),
				$this->getConfPost());
			if ($response->getStatus() == "200") {
				$content = $response->asJson();
				
				if (is_array($content)) {
					$account_fields = $content;
				}
			}
			
			// Get Shop fields
			$shop_fields = null;
			$response = HttpRequester::post(
				$this->buildUrl(Configuration::getGlobalValue('VD_FORM_SIGNUP_SHOP')),
				$this->getConfPost());
			if ($response->getStatus() == "200") {
				$content = $response->asJson();

				if (is_array($content)) {
					$shop_fields = $content;
				}
			}
		}
		catch (Exception $ex) {
			Tools::d($ex);
		}
		
		if ($account_fields != null && $shop_fields != null) {
			$form = $this->buildFormStep1($account_fields, $shop_fields, array(
				'submit' => 'submitSignUp_Step1',
				'isMultiShop' => $isMultiShop
			));
			
			$this->context->smarty->assign(array(
				'form' => Tools::jsonEncode($form),
				'form_action' => Configuration::getGlobalValue('VD_FORM_SIGNUP_VALIDATION')
			));
		}
		
		$help = array(
			"src" => $this->buildUrl(Configuration::get('VD_HELP_SIGNUP_URL')),
			"width" => Configuration::get('VD_HELP_SIGNUP_WIDTH'),
			"height" => Configuration::get('VD_HELP_SIGNUP_HEIGHT')
		);
		
		$this->context->smarty->assign(array(
			'help' => $help,
			'lang_iso' => $this->context->language->iso_code,
			'request_uri' => $_SERVER['REQUEST_URI'],
			'token_videodesk' => Tools::getAdminTokenLite('AdminModules', $this->context->getContext()),
			'module_path' => $this->module->getPathUri()
		));
	}
	
	/**
	 * Display Sign Up page - Step 2
	 */
	public function processDisplaySignUpStep2()
	{
		$this->_display = true;
		$this->_need_form = true;
		$this->_template = 'signup-step-2';
		
		$isMultiShop = $this->isMultiShops();
		
		// Keep the shops for which a configuration as been set
		$nb_shops = 0;
		$shops = $this->getShops();
		foreach ($shops as $id_shop_group => $shop_group) {
			foreach ($shop_group['shops'] as $key => $shop) {
				if (!isset($shop['configuration']['website_id'])) {
					unset($shops[$id_shop_group]['shops'][$key]);
				}
				else {
					$nb_shops++;
				}
			}
		}
		
		$id_current_employee = $this->context->employee->id;
		$employees_ordered = array();
		$employees = Employee::getEmployees();
		foreach ($employees as $employee) {
			if ($employee['id_employee'] == $id_current_employee) {
				$current_employee = $employee;
			}
			else {
				$employees_ordered[] = $employee;
			}
		}
		$employees_ordered = array_merge(array($current_employee), $employees_ordered);
		
		$this->context->smarty->assign(array(
			'lang_iso' => $this->context->language->iso_code,
			'token_videodesk' => Tools::getAdminTokenLite('AdminModules', $this->context->getContext()),
			'module_path' => $this->module->getPathUri(),
			'isMultiShop' => $isMultiShop,
			'shops' => $shops,
			'nb_shops' => $nb_shops,
			'employees' => $employees_ordered,
			'idCurrentEmployee' => $id_current_employee
		));
	}
	
	/**
	 * Display Sign Up page - Step 3
	 */
	public function processDisplaySignUpStep3()
	{
		$shops = Tools::getValue('shop');
		
		$this->_display = true;
		$this->_need_form = true;
		$this->_template = 'signup-step-3';
		$isMultiShop = $this->isMultiShops();
		
		// Get Account fields
		$agents_fields = null;
		try {
			$response = HttpRequester::post(
				$this->buildUrl(Configuration::getGlobalValue('VD_FORM_SIGNUP_AGENT')),
				$this->getConfPost());
			if ($response->getStatus() == "200") {
				$content = $response->asJson();
				
				if (is_array($content)) {
					$agents_fields = $content;
				}
			}
			
			// Get Shop fields
			$websites_form = array();
			$websites = VideodeskShopConfiguration::getWebsiteIds();
			$conf = $this->getConfPost();
			foreach ($websites as $id_shop => $website_id) {
				$conf['uid_site'] = $website_id;
				$response = HttpRequester::post(
					$this->buildUrl(Configuration::getGlobalValue('VD_FORM_SIGNUP_SHOP_AGENT')),
					$conf);
				if ($response->getStatus() == "200") {
					$content = $response->asJson();
					
					if (is_array($content)) {
						$websites_form[$id_shop] = $content;
					}
				}
			}
		}
		catch (Exception $ex) {
			Tools::d($ex);
		}
		
		if ($agents_fields != null && count($websites_form) > 0) {
			$form = $this->buildFormStep3($agents_fields, $websites_form, array(
				'submit' => 'submitSignUp_Step3',
				'isMultiShop' => $isMultiShop
			));
			
			$this->context->smarty->assign(array(
				'form' => Tools::jsonEncode($form),
				'form_action' => Configuration::getGlobalValue('VD_FORM_SIGNUP_VALIDATION')
			));
		}
		
		$this->context->smarty->assign(array(
			'shops' => $shops,
			'lang_iso' => $this->context->language->iso_code,
			'request_uri' => $_SERVER['REQUEST_URI'],
			'token_videodesk' => Tools::getAdminTokenLite('AdminModules', $this->context->getContext()),
			'module_path' => $this->module->getPathUri()
		));
	}
	
	/**
	 * Update Global Configuration during module install
	 */
	public function updateConfiguration()
	{
		$response = HttpRequester::post(
			$this->buildUrl($this->module->_videodesk_main_url),
			$this->getConfPost());
		if ($response->getStatus() == "200") {
			$content = $response->asJson();
			
			// General configuration
			$config = $content->config;
			// Notification URLs
			Configuration::updateGlobalValue('VD_NOTIF_VDCOM', $config->notification_url->videodesk);
			Configuration::updateGlobalValue('VD_NOTIF_VDPSP', $config->notification_url->prestashop);
			// Standard links
			Configuration::updateGlobalValue('VD_READMORE', $config->readmore_url);
			Configuration::updateGlobalValue('VD_PRICING', $config->pricing_url);
			Configuration::updateGlobalValue('VD_CGU', $config->cgu_url);
			// Goal notification for statistics
			Configuration::updateGlobalValue('VD_NOTIF_OBJECTIVE', $config->validate_command);
			// Edito
			Configuration::updateGlobalValue('VD_EDITO_URL', $config->edito_iframe->url);
			Configuration::updateGlobalValue('VD_EDITO_WIDTH', $config->edito_iframe->width);
			Configuration::updateGlobalValue('VD_EDITO_HEIGHT', $config->edito_iframe->height);
			// Sign Up Help
			Configuration::updateGlobalValue('VD_HELP_SIGNUP_URL', $config->signup_help_iframe->url);
			Configuration::updateGlobalValue('VD_HELP_SIGNUP_WIDTH', $config->signup_help_iframe->width);
			Configuration::updateGlobalValue('VD_HELP_SIGNUP_HEIGHT', $config->signup_help_iframe->height);
			// Module in Back Office
			Configuration::updateGlobalValue('VD_BO_MODULE_JS', $config->backoffice_module_url);
			// Preview
			Configuration::updateGlobalValue('VD_PREVIEW_URL', $config->preview_url);
			// BO Videodesk URLs
			Configuration::updateGlobalValue('VD_BO_HOME', $config->backoffice_url->home);
			Configuration::updateGlobalValue('VD_BO_TEMPLATE', $config->backoffice_url->template);
			Configuration::updateGlobalValue('VD_BO_TEXTS', $config->backoffice_url->texts);
			Configuration::updateGlobalValue('VD_BO_MESSAGES', $config->backoffice_url->pretyped_messages);
			Configuration::updateGlobalValue('VD_BO_AGENT', $config->backoffice_url->agent);
			
			// Interfaces
			$interfaces = $content->interfaces;
			// Email - Videodesk account exists with this email address
			Configuration::updateGlobalValue('VD_EMAIL_CHECK', $interfaces->email_check);
			// Sign In - Website Id validation
			Configuration::updateGlobalValue('VD_FORM_SIGNIN', $interfaces->signin);
			// Sign Up - General informations
			Configuration::updateGlobalValue('VD_FORM_SIGNUP_ACCOUNT', $interfaces->signup->account_form);
			Configuration::updateGlobalValue('VD_FORM_SIGNUP_SHOP', $interfaces->signup->shop_form);
			Configuration::updateGlobalValue('VD_FORM_SIGNUP_ACCOUNT_SUB', $interfaces->signup->account_form_submit);
			Configuration::updateGlobalValue('VD_FORM_SIGNUP_SHOP_SUB', $interfaces->signup->shop_form_submit);
			// Sign Up - Agents
			Configuration::updateGlobalValue('VD_FORM_SIGNUP_AGENT', $interfaces->agent_signup->agent_form);
			Configuration::updateGlobalValue('VD_FORM_SIGNUP_AGENT_SUB', $interfaces->agent_signup->agent_form_submit);
			Configuration::updateGlobalValue('VD_FORM_SIGNUP_SHOP_AGENT', $interfaces->agent_signup->shop_agent_form);
			Configuration::updateGlobalValue('VD_FORM_SIGNUP_SHOP_AGENT_SUB', $interfaces->agent_signup->shop_agent_form_submit);
			
			return true;
		}
		else {
			$this->_errors[] = $this->module->l('Unable to retrieve videodesk configuration. Please verify your Internet connection');
			return false;
		}
	}
	
	/**
	 * Add standard values as GET parameters to URL
	 */
	public function buildUrl($url)
	{
		$prefix = '&';
		if (strpos($url, '?') === false)
			$prefix = '?';
		
		return $url . $prefix . 'lang=' . $this->context->language->iso_code . '&platform=' . $this->module->_platform . '&version=' . $this->module->version . '&affiliate=' . $this->module->module_key;
	}
	
	/**
	 * Send installation notifications to the sources
	 */ 
	public function sendInstallNotification()
	{
		if (is_array($this->module->_source) && !empty($this->module->_source)) {
			foreach ($this->module->_source as $key => $source) {
				$isSent = Configuration::get('VD_NOTIF_SENT_' . $source, false);
				if (!$isSent) {
					$prestashop = false;
					if ($source == 'VDPSP') {
						$prestashop = true;
					}
					$url = $this->buildNotificationUrl($source, Configuration::get('VD_NOTIF_' . $source));
					try {
						$response = HttpRequester::get($url);
						if ($response->getStatus() == "200") {
							Configuration::updateGlobalValue('VD_NOTIF_SENT_' . $source, 1);
						}
					}
					catch (Exception $ex) {
						return true;
					}
				}
			}
			return true;
		}
		return false;
	}
	
	/**
	 * Add standard values as GET parameters to URL for notification
	 */
	public function buildNotificationUrl($source, $url, $prestashop = null)
	{
		$prefix = '&';
		if (strpos($url, '?') === false)
			$prefix = '?';
		$url = $url . $prefix;
		
		$url .= 'firstname=' . urlencode($this->context->employee->firstname);
		$url .= '&lastname=' . urlencode($this->context->employee->lastname);
		$url .= '&email=' . urlencode($this->context->employee->email);
		$url .= '&prestashopversion=' . urlencode(_PS_VERSION_);
		if ($prestashop == true) {
			$url .= '&module=' . urlencode($this->module->name);
		}
		$url .= '&vdmoduleversion=' . urlencode($this->module->version);
		$url .= '&affiliate=' . $source;
		
		$activeShops = Shop::getContextListShopID();
		$sql = 'SELECT `id_shop` 
			FROM `' . _DB_PREFIX_ . 'module_shop`
        	WHERE `id_module` = ' . $this->module->id . ' AND `id_shop` IN(' . implode(', ', $activeShops) . ')';
		$shops = Db::getInstance()->executeS($sql);
		if (is_array($shops) && !empty($shops)) {
			foreach ($shops as $key => $shop) {
				$shop = new Shop($shop['id_shop']);
				$url .= '&name' . $key . '=' . urlencode($shop->name);
				$urls = $shop->getUrls();
				if (is_array($urls) && !empty($urls)) {
					foreach ($urls as $key => $urlShop) {
						$urlShop = new ShopUrl($urlShop['id_shop_url']);
						$url .= '&url' . $key . '=' . urlencode($urlShop->getURL());
					}
				}
			}
		}
		return $url;
	}
	
	/**
	 * Forms - Add hidden fields of configuration datas (lang, key, platform...)
	 */ 
	public function buildFormConf()
	{
		$form = array();
		$structure_name = "conf";

		$field_tmp = new stdClass();
		$field_tmp->type = "hidden";
		$field_tmp->name = $structure_name . "[lang]";
		$field_tmp->value = $this->context->language->iso_code;
		$form[] = $field_tmp;

		$field_tmp = new stdClass();
		$field_tmp->type = "hidden";
		$field_tmp->name = $structure_name . "[platform]";
		$field_tmp->value = $this->module->_platform;
		$form[] = $field_tmp;

		$field_tmp = new stdClass();
		$field_tmp->type = "hidden";
		$field_tmp->name = $structure_name . "[version]";
		$field_tmp->value = $this->module->version;
		$form[] = $field_tmp;

		$field_tmp = new stdClass();
		$field_tmp->type = "hidden";
		$field_tmp->name = $structure_name . "[key]";
		$field_tmp->value = $this->module->module_key;
		$form[] = $field_tmp;

		$field_tmp = new stdClass();
		$field_tmp->type = "hidden";
		$field_tmp->name = $structure_name . "[affiliate]";
		$field_tmp->value = $this->module->_source[0];
		$form[] = $field_tmp;

		$field_tmp = new stdClass();
		$field_tmp->type = "hidden";
		$field_tmp->name = "token";
		$field_tmp->value = $this->module->getToken();
		$form[] = $field_tmp;

		return $form;
	}
	
	/**
	 * Forms - Get fields of configuration datas (lang, key, platform...) for POST requests
	 */
	public function getConfPost()
	{
		return $this->module->getConfPost();
	}
	
	/**
	 * Forms - Generate form structure for Step 1
	 */
	public function buildFormStep1($account_fields, $shop_fields, $params = null)
	{
		$form = array();
		$form_last_fields = array();
		
		$uid = Configuration::getGlobalValue('VD_ACCOUNT_UID');
		
		if ($uid == false) {
			
			// Acount form
			foreach ($account_fields as &$field) {
				$field_tmp = $this->formFieldInit();
				
				$field->name = "account[" . $field->name . "]";
				
				if (isset($field->mapping)) {
					$this->formFieldMapping($field);
					unset($field->mapping);
				}
				
				$field_tmp->html = array($field);
				
				if ($field->id == 'is_accept') {
					$field->caption = ' <a href="' . $this->buildUrl(Configuration::getGlobalValue('VD_CGU')) . '" class="cgu" target="_blank">' . $field->caption . '</a>';
					$field_tmp->class = 'is_accept checkbox clearfix';
					$form_last_fields[] = $field_tmp;
				}
				else {
					$form[] = $field_tmp;
				}
				
				if (isset($field->required) && $field->required) {
					$field->caption .= " <span class='field_required'>*</span>";
					unset($field->required);
				}
			}
		}
		
		// Shop forms
		$shop_list = $this->getShops();
		// Loop on shop groups
		foreach ($shop_list as $key => $shop_group) {
			//                 $shop_group_form = array();
			$shop_group_form = $this->formFieldInit();
			
			$field_tmp = $this->formFieldInit("div", "shop_group clearfix");
			
			// Multishop => Add a section with the shop group name
			if ($params['isMultiShop']) {
				$field_shop_group_title = $this->formFieldInit("h2");
				$field_shop_group_title->html = $shop_group['name'];
				
				$shop_group_form->html[] = $field_shop_group_title;
			}
			
			$shop_group_form = $field_tmp;
			
			// Loop on shops
			foreach ($shop_group['shops'] as $key_shop => $shop) {
				$shop_object = new Shop($shop['id_shop']);
				$shop_form = array();
				
				$field_shop = $this->formFieldInit("div", "shop clearfix");
				
				$field_shop_title = $this->formFieldInit("h3");
				
				// Multishop => Add a section with the shop name
				if ($params['isMultiShop']) {
					$field_shop_title->html = $shop['name'] . '<span>&nbsp;</span>';
				}
				
				foreach ($shop_fields as $key_field => $field) {
					$shop_field = clone $field;
					$field_tmp = $this->formFieldInit();
					
					$shop_field->name = "shop[" . $shop['id_shop'] . "][" . $shop_field->name . "]";
					$shop_field->id = "shop_" . $shop['id_shop'] . "_" . $shop_field->id;
					
					if (isset($shop_field->mapping)) {
						$this->formFieldMapping($shop_field, $shop['id_shop']);
						unset($shop_field->mapping);
					}
					
					// Special process for Logo
					if (isset($shop_field->isLogo) && $shop_field->isLogo) {
						$logo = "<img src='" . _PS_IMG_ . $shop_field->value . "' height='50px' />";
						$field_logo = $this->formFieldInit("p", "shop_logo");
						$field_logo->html = $logo;
						
						$field_logo_url = $this->formFieldInit("hidden");
						$field_logo_url->name = "shop[" . $shop['id_shop'] . "][logo_url]";
						$field_logo_url->value = $shop_object->getBaseURL(). 'img/' . $shop_field->value;
						
						$field_tmp->class = "shop_logo_container";
						$field_tmp->html = array(
							$shop_field,
							$field_logo,
							$field_logo_url
						);
					}
					// Special process for shop languages that are unavailable in Videodesk
					elseif (isset($shop_field->unavailable_languages)) {
						$field_unavailable_languages = $this->formFieldInit("p", "unavailable_languages");
						$unavailable_label = '';
						foreach ($shop_field->unavailable_languages as $lang) {
							$unavailable_label .= strtoupper($lang).', ';
						}
						$unavailable_label = substr($unavailable_label, 0, strlen($unavailable_label) - 2);
						$unavailable_label .= ' '.$this->module->l('version, please note: the Videodesk module is not available in this language and will be displayed in english. Ask us a translation: <a href="mailto:contact@videodesk.com">contact@videodesk.com</a>');
						
						$field_unavailable_languages->html = $unavailable_label;
						
// 						$field_tmp->class = "shop_logo_container";
						$field_tmp->html = array(
								$shop_field,
								$field_unavailable_languages
						);
						unset($shop_field->unavailable_languages);
					}
					elseif ($field->id == 'is_use_videodesk') {
						$field_tmp->class = 'checkbox clearfix';
						$shop_field->caption .= " " . $shop['name'];
						$shop_field->checked = "checked";
						$field_tmp->html = array(
							$shop_field
						);
					}
					else {
						$field_tmp->html = array(
							$shop_field
						);
					}
					
					if (isset($shop_field->required) && $shop_field->required) {
						$shop_field->caption .= " <span class='field_required'>*</span>";
						unset($shop_field->required);
					}
					
					$shop_form[] = $field_tmp;
				}
				
				// Shop name
				$shop_name = $this->formFieldInit('hidden');
				$shop_name->name = "shop[" . $shop['id_shop'] . "][shop_name]";
				$shop_name->id = "shop_" . $shop['id_shop'] . "_shop_name";
				$shop_name->mapping = 'shop_name';
				$this->formFieldMapping($shop_name, $shop['id_shop']);
				unset($shop_name->mapping);
				$shop_form[] = $shop_name;
				
				$field_shop->html = array_merge(array(
					$field_shop_title
				), $shop_form);
				$shop_group_form->html[] = $field_shop;
			}
			$form[] = $shop_group_form;
		}
		
		$form = array_merge($form, $form_last_fields);
		$form = array_merge($form, $this->buildFormConf());
		
		// Action input
		$action_input = $this->formFieldInit('hidden');
		$action_input->name = 'signup_action';
		$action_input->value = $params['submit'];
		$form[] = $action_input;
		
		// Submit button
		$field_tmp = $this->formFieldInit();
		$submit = $this->formFieldInit('submit', 'blueButton');
		$submit->name = $params['submit'];
		$submit->value = $this->module->l('Submit');
		$field_tmp->html = $submit;
		$form[] = $field_tmp;
		
		return $form;
	}
	
	/**
	 * Forms - Generate form structure for Step 3
	 */
	public function buildFormStep3($agent_fields, $websites_form, $params = null)
	{
		$form = array();
		$form_last_fields = array();
		
		$employee_shops = Tools::getValue("shop");
		
		foreach ($employee_shops as $id_employee => $employees) {
			$agent_form = array();
			$employee = new Employee($id_employee);
			$employee_name = $employee->firstname . ' ' . $employee->lastname;
			
			$fields_agent = $this->formFieldInit("p", "agent_form agent_form_container clearfix");
			
			$field_agent_title = $this->formFieldInit("h3");
			$field_agent_title->id = "agent_form_" . $id_employee;
			$field_agent_title->html = $employee_name . '<span>&nbsp;</span>';
			$agent_form[] = $field_agent_title;
			
			if ($id_employee == $this->context->employee->id) {
				$current_employee = true;
				$uid = Configuration::getGlobalValue('VD_ACCOUNT_UID_AGENT');
			}
			else {
				$current_employee = false;
				$uid = "";
			}
			
			// Agent account form
			foreach ($agent_fields as &$field) {
				$agent_field = clone $field;
				$field_tmp = $this->formFieldInit();
				
				// Special display for the current employee
				if ($current_employee) {
					if ($agent_field->name == 'email') {
						$agent_field->readonly = 'readonly';
					}
					elseif ($agent_field->name == 'last_name' || $agent_field->name == 'first_name' || $agent_field->name == 'password' || $agent_field->name == 'password_confirm') {
						$field_tmp->class = 'hide';
					}
				}
				
				$agent_field->name = "agent[" . $id_employee . "][agent_account][" . $agent_field->name . "]";
				
				if (isset($agent_field->mapping)) {
					$this->formFieldMapping($agent_field, null, array(
						'current' => $current_employee,
						'id_employee' => (int) $id_employee,
						'uid' => $uid
					));
					unset($agent_field->mapping);
				}
				
				if (isset($agent_field->required) && $agent_field->required) {
					$agent_field->caption .= " <span class='field_required'>*</span>";
					unset($agent_field->required);
				}
				
				if ($agent_field->id == 'language') {
					$options = array();
					$checked = false;
					foreach ($agent_field->options as $value => $label) {
						$lang_id = Language::getIdByIso($value);
						if ($lang_id != null) {
							$caption = "<img alt=" . $label . " title=" . $label . " src='" . _PS_IMG_ . "l/" . $lang_id . "' />";
						}
						else {
							$caption = $label;
						}
						$options[$value] = array(
							'caption' => $caption
						);
						// Check the employee language if in the list
						if (strtolower($value) == strtolower($this->context->language->iso_code)) {
							$checked = true;
							$options[$value] = array_merge($options[$value], array(
								'checked' => 'checked'
							));
						}
						// Check English by defaut if current language not in the list
						if (strtolower($value) == "en" && !$checked) {
							$options[$value] = array_merge($options[$value], array(
								'checked' => 'checked'
							));
						}
					}
					$agent_field->options = $options;
				}
				
				$field_tmp->html = array(
					$agent_field
				);
				
				if ($agent_field->id == 'is_accept') {
					$form_last_fields[] = $field_tmp;
				}
				else {
					$agent_form[] = $field_tmp;
				}
			}
			
			$field_tmp = $this->formFieldInit('p', 'hidden');
			$field_tmp->name = "agent[" . $id_employee . "][agent_account][uid]";
			$field_tmp->value = $uid;
			$agent_form[] = $field_tmp;
			
			$fields_agent->html = $agent_form;
			
			// Shops of the agent form
			foreach ($employees as $id_shop) {
				$shop_form = array();
				
				$shop = new Shop($id_shop);
				
				$fields_shop = $this->formFieldInit('p', 'agent_shop clearfix');
				
				$field_shop_title = $this->formFieldInit('h4');
				$field_shop_title->html = $employee_name . ' - ' . $shop->name . "<span>&nbsp;</span>";
				
				// Fields of agent shop form
				foreach ($websites_form[$id_shop] as $key => &$field_shop) {
					$shop_field = clone $field_shop;
					
					if (isset($shop_field->name))
						$shop_field->name = "agent[" . $id_employee . "][shop][shop" . $id_shop . "][" . $shop_field->name . "]";
					
					if (isset($shop_field->mapping)) {
						if ($shop_field->mapping == "videodesk_languages") {
							$field_lang = $this->formFieldInit('p', 'languages');
							$field_lang->html = "<label>" . $shop_field->fields->language_level->caption . "</label>";
							
							$field_tmp = $this->formFieldInit();
							$field_tmp->html = array(
								$field_lang
							);
							
							foreach ($shop_field->languages as $lang_f) {
								$languages = Language::getLanguages(true, $id_shop);
								$lang_exist = false;
								foreach ($languages as $prestalang) {
									if ($prestalang['iso_code'] == $lang_f) {
										$lang_exist = true;
									}
								}
								
								if ($lang_exist) {
									$field_lang_level = clone $shop_field->fields->language_level;
									$field_lang_level->name = "agent[" . $id_employee . "][shop][shop" . $id_shop . "][langs][" . $lang_f . "][language_level]";
									// 									$field_tmp1->caption = $lang_f;
									
									$img = "<img alt=" . $lang_f . " title=" . $lang_f . " src='" . _PS_IMG_ . "l/" . Language::getIdByIso($lang_f) . "' />";
									$field_lang_level->caption = $img;
									$last_value = array();
									foreach ($field_lang_level->options as $value => $label) {
										$last_value['value'] = $value;
										$last_value['label'] = $label;
// 										$field_lang_level->options[$value] = array(
// 											"checked" => "checked",
// 											"caption" => $label
// 										);
// 										break;
									}
									$field_lang_level->options[$last_value['value']] = array("checked" => "checked", "caption" => $last_value['label']);
									
									$field_lang_level_container = $this->formFieldInit("p", "lang_level");
									$field_lang_level_container->html = $field_lang_level;
									
									$field_title = clone $shop_field->fields->job_title;
									$field_title->name = "agent[" . $id_employee . "][shop][shop" . $id_shop . "][langs][" . $lang_f . "][" . $field_title->name . "]";
									$field_title->value = $this->module->l('Agent');
									$field_title_container = $this->formFieldInit("p", "lang_job");
									$field_title_container->html = $field_title;
									
									$field_tmp->html = array_merge(
										$field_tmp->html,
										array($field_lang_level_container),
										array($field_title_container)
									);
								}
							}
							$shop_form[] = $field_tmp;
							unset($shop_field);
						}
						else {
							$this->formFieldMapping($shop_field, $id_shop, array('id_employee' => $id_employee));
							unset($shop_field->mapping);
						}
					}
					
					if (isset($shop_field) && ($shop_field->id == 'agent_access')) {
						$last_value = array();
						foreach ($shop_field->options as $value => $label) {
							$last_value = array('value' => $value, 'label' => $label);
							
						}
						$shop_field->options->$last_value['value'] = array(
								"checked" => "checked",
								"caption" => $last_value['label']
						);
					}
					
					if (isset($shop_field->required) && $shop_field->required) {
						$shop_field->caption .= " <span class='field_required'>*</span>";
						unset($shop_field->required);
					}
					
					if (isset($shop_field)) {
						$field_tmp = $this->formFieldInit();
						$field_tmp->html = array(
							$shop_field
						);
						$shop_form[] = $field_tmp;
					}
				}
				$fields_shop->html = array_merge(array($field_shop_title), $shop_form);
				$fields_agent->html[] = $fields_shop;
			}
			
			// Submit button
			$field_tmp = $this->formFieldInit();
			$submit = $this->formFieldInit('submit', 'blueButton');
			$submit->name = "agent[" . $id_employee . "][submitSignUp_Step3]";
			$submit->value = $this->module->l('Submit');
			$field_tmp->html = $submit;
			$fields_agent->html[] = $field_tmp;
			
			$form[] = $fields_agent;
		}
		
		$form = array_merge($form, $this->buildFormConf());
		
		$action_input = new stdClass();
		$action_input->type = 'hidden';
		$action_input->name = 'signup_action';
		$action_input->value = $params['submit'];
		$form[] = $action_input;
		
		return $form;
	}
	
	/**
	 * Forms - General structure of a field handler
	 */
	private function formFieldInit($type = "p", $class = "text")
	{
		$field = new stdClass();
		$field->type = $type;
		$field->class = $class;
		
		return $field;
	}
	
	/**
	 * Forms - Handle mapping field to prefill values
	 */
	private function formFieldMapping($field, $id_shop = null, $employee = null)
	{
		if (!is_null($employee)) {
			$employee = new Employee($employee['id_employee']);
		}
		
		// Shop e-mail
		if ($field->mapping == "shop_email") {
			$field->value = $this->context->employee->email;
			$field->readonly = "readonly";
		}
		// Shop name
		elseif ($field->mapping == "shop_name") {
			if ($id_shop != null) {
				$shop = new Shop($id_shop);
				$field->value = $shop->name;
			}
			else
				$field->value = Configuration::getGlobalValue('PS_SHOP_NAME');
		}
		// Shop phone
		elseif ($field->mapping == "shop_phone") {
			$phone = Configuration::getGlobalValue('PS_SHOP_PHONE');
			if ($phone === false || $phone == null) {
				$phone = Configuration::getGlobalValue('blockcontactinfos_phone');
				if ($phone === false)
					$phone = '';
			}
			$field->value = $phone;
		}
		// Employee email
		elseif ($field->mapping == "employee_email") {
			if (is_null($employee)) {
				$field->value = $this->context->employee->email;
			}
			else {
				$field->value = $employee->email;
			}
		}
		// Employee firstname
		elseif ($field->mapping == "employee_firstname") {
			if (is_null($employee)) {
				$field->value = $this->context->employee->firstname;
			}
			else {
				$field->value = $employee->firstname;
			}
		}
		// Employee lastname
		elseif ($field->mapping == "employee_lastname") {
			if (is_null($employee)) {
				$field->value = $this->context->employee->lastname;
			}
			else {
				$field->value = $employee->lastname;
			}
		}
		// Employee display name
		elseif ($field->mapping == "employee_display_name") {
			if (is_null($employee)) {
				$field->value = $this->context->employee->firstname . ' ' . $this->context->employee->lastname;
			}
			else {
				$field->value = $employee->firstname . ' ' . $employee->lastname;
			}
		}
		// Company
		elseif ($field->mapping == "company") {
			$field->value = Configuration::getGlobalValue('PS_SHOP_NAME');
		}
		// Shop logo
		elseif ($field->mapping == "shop_logo") {
			if ($logo_filename = Configuration::get('PS_LOGO', null, null, $id_shop)) {
				$field->value = $logo_filename;
				$field->isLogo = true;
			}
		}
		// Shop main URL
		elseif ($field->mapping == "shop_main_url") {
			foreach (ShopUrl::getShopUrls($id_shop) as $shopUrl) {
				if ($shopUrl->main) {
					$field->value = (Configuration::get('PS_SSL_ENABLED', null, null, $id_shop) ? 'https://' : 'http://') . $shopUrl->domain;
					break;
				}
			}
		}
		// Shop test URL
		elseif ($field->mapping == "shop_test_url") {
			foreach (ShopUrl::getShopUrls($id_shop) as $shopUrl) {
				if (!$shopUrl->main) {
					$field->value = $shopUrl->domain;
					break;
				}
			}
		}
		// Country list
		elseif ($field->mapping == "country_list") {
			$countries = Country::getCountries($this->context->language->id);
			$country_select = array();
			foreach ($countries as $country) {
				if (strtolower($country['iso_code']) == strtolower($this->context->country->iso_code))
					$country_select[$country['iso_code']] = array(
						"selected" => "selected",
						"html" => $country['name']
					);
				else
					$country_select[$country['iso_code']] = $country['name'];
			}
			$field->options = $country_select;
		}
		// Language list
		elseif ($field->mapping == "shop_language_list") {
			$languages = Language::getLanguages(true);
			$language_select = array();
			$unavailable_languages = array();
			foreach ($languages as $language) {
				if (isset($language['shops'][$id_shop]) && $language['shops'][$id_shop] == 1) {
					$language_select[$language['iso_code']] = array(
						"checked" => "checked",
						"caption" => '<img src="../img/l/' . $language['id_lang'] . '.jpg" alt="' . $language['name'] . '" title="' . $language['name'] . '" />'
					);
				}
				foreach ($field->available_languages as $language_vd) {
					$found = false;
					if ($language_vd == $language['iso_code']) {
						$found = true;
						break;
					}
				}
				
				if (!$found) {
					$unavailable_languages[] = $language['iso_code'];
				}
			}
			
			if (count($unavailable_languages) > 0) {
				$field->unavailable_languages = $unavailable_languages;
			}
			$field->options = $language_select;
			$field->name .= "[]";
		}
	}
	
	/**
	 * Shops - Get the list of shops by group
	 */
	public function getShops($shop_context = null)
	{
		$shops = array();
		// Get shops for context
		if (is_null($shop_context)) {
			$shops = Shop::getTree();
		}
		else {
			switch ($shop_context) {
				case 'group':
					$group = (array) Shop::getContextShopGroup();
					$id_shop_group = Shop::getContextShopGroupID();
					if (is_null($id_shop_group)) {
						$context = Context::getContext();
						$id_shop_group = $context->shop->id_shop_group;
					}
					$shops_group = Shop::getShops(null, $id_shop_group);
					$group['shops'] = $shops_group;
					array_push($shops, $group);
					break;
				case 'shop':
					$group = (array) Shop::getContextShopGroup();
					$id_shop = Shop::getContextShopID();
					if (is_null($id_shop)) {
						$context = Context::getContext();
						$id_shop = $context->shop->id;
					}
					$shop = (array) Shop::getShop($id_shop);
					$group['shops'] = array(
						$shop
					);
					array_push($shops, $group);
					break;
				default:
					$shops = Shop::getTree();
			}
		}
		
		// Get configuration of each shops
		if (!empty($shops) && is_array($shops)) {
			foreach ($shops as $shops_group_key => $shops_group) {
				if (!empty($shops_group['shops']) && is_array($shops_group['shops'])) {
					foreach ($shops_group['shops'] as $key => $shop) {
						$sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'videodesk_shop_configuration AS c WHERE c.id_shop = ' . $shop['id_shop'];
						$result = Db::getInstance()->getRow($sql);
						$result['progressBar'] = $this->getProgress($shop['id_shop']);
						if ($result !== false) {
							$shops[$shops_group_key]['shops'][$key]['configuration'] = $result;
						}
					}
				}
			}
		}
		return $shops;
	}
	
	/**
	 * Retrieve the progress status of a customization
	 */
	public static function getProgress($id_shop)
	{
		$sql = 'SELECT (100 / ' . self::$_nbStep . ') * (progress_criterias + progress_colors + progress_texts + progress_messages + progress_agent) FROM ' . _DB_PREFIX_ . 'videodesk_shop_configuration AS c WHERE c.id_shop = ' . $id_shop;
		$progress = (int) Db::getInstance()->getValue($sql);
		return $progress;
	}
	
	/**
	 * Retrieve the criterias for Advanced Configuration
	 */
	public static function getCriterias($id_shop = null)
	{
		$criterias = array();
		$sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'videodesk_shop_configuration_criteria AS c';
		$criterias = Db::getInstance()->executeS($sql);
		if (!empty($criterias) && !empty($id_shop)) {
			foreach ($criterias as $key => $criteria) {
				$sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'videodesk_shop_configuration_criteria_value AS c WHERE c.id_shop = ' . $id_shop . ' AND c.id_criteria = ' . $criteria['id_criteria'] . ';';
				$criteria_value = Db::getInstance()->getRow($sql);
				$criterias[$key]['active'] = $criteria_value['active'];
				$criterias[$key]['value'] = $criteria_value['value'];
			}
		}
		return $criterias;
	}
	
	/**
	 * Retrieve the group pages for Advanced Configuration
	 */
	public static function getGroupsPages($id_shop)
	{
		$groups_pages = array();
		$sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'videodesk_shop_configuration_group_pages AS g ORDER BY g.position ASC';
		$groups_pages = Db::getInstance()->executeS($sql);
		if (!empty($groups_pages) && !empty($id_shop)) {
			foreach ($groups_pages as $key => $group) {
				$sql = 'SELECT p.*, IF (v.id_page IS NULL, 0, 1) AS active FROM ' . _DB_PREFIX_ . 'videodesk_shop_configuration_page AS p LEFT JOIN ' . _DB_PREFIX_ . 'videodesk_shop_configuration_page_value AS v ON p.id_page = v.id_page WHERE p.id_group_pages = ' . $group['id_group_pages'] . ' GROUP BY p.id_page;';
				$pages = Db::getInstance()->executeS($sql);
				$groups_pages[$key]['pages'] = $pages;
			}
		}
		return $groups_pages;
	}
	
	/**
	 * Retrieve the additionnal module controllers for Advanced Configuration
	 */
	public static function getModulesControllers()
	{
		$modules = array();
		foreach (glob(_PS_MODULE_DIR_ . '*/controllers/front/*.php') as $file) {
			$filename = basename($file, '.php');
			if ($filename == 'index')
				continue;
			
			$module = basename(dirname(dirname(dirname($file))));
			$page = VideodeskShopConfigurationPageValue::getActiveValue('modules', Tools::getValue('id_shop'), 'module-' . $module . '-' . $filename);
			$modules[] = array(
				'id_module' => 'module-' . $module . '-' . $filename,
				'name' => $module . ' - ' . $filename,
				'active' => $page['active']
			);
		}
		return $modules;
	}
	
	/**
	 * Retrieve the catalog categories for Advanced Configuration
	 */
	public function getCategoriesTree($id_category = null)
	{
		$categories = array();
		if (!empty($id_category)) {
			$category = new Category($id_category, $this->context->language->id);
			$childrenArray = $category->getChildrenWs();
			foreach ($childrenArray as $child_key => $child) {
				$category = new Category($child['id'], $this->context->language->id);
				$page = VideodeskShopConfigurationPageValue::getActiveValue('categories', Tools::getValue('id_shop'), $category->id);
				$categories[$category->id] = array(
					'id_category' => $category->id,
					'name' => $category->name,
					'level_depth' => $category->level_depth,
					'active' => $page['active']
				);
				$categories[$category->id]['children'] = $this->getCategoriesTree($category->id);
			}
		}
		else {
			$rootCategory = $this->context->shop->getCategory();
			if (!empty($rootCategory)) {
				$category = new Category($rootCategory, $this->context->language->id);
				$page = VideodeskShopConfigurationPageValue::getActiveValue('categories', Tools::getValue('id_shop'), $category->id_category);
				$categories[$category->id_category] = array(
					'id_category' => $category->id_category,
					'name' => $category->name,
					'level_depth' => $category->level_depth,
					'active' => $page['active']
				);
				$categories[$category->id_category]['children'] = $this->getCategoriesTree($category->id_category);
			}
		}
		return $categories;
	}
	
	/**
	 * Check if a context is MultiShop
	 */
	public function isMultiShops()
	{
		if (!Shop::isFeatureActive() || Shop::getTotalShops(false, null) < 2)
			return false;
		return true;
	}
	
	/**
	 * Getter for template file
	 */
	public function getTemplate()
	{
		return $this->_template;
	}
	
	/**
	 * Getter for errors
	 */
	public function getErrors()
	{
		return $this->_errors;
	}
	
	/**
	 * Getter for display
	 */
	public function display()
	{
		return $this->_display;
	}
	
	/**
	 * Getter for need form
	 */
	public function needForm()
	{
		return $this->_need_form;
	}
}
