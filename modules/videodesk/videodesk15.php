<?php
/**
 * Module videodesk - Bootstrap for PrestaShop 1.5.X
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

// Loading Models
require_once(_PS_MODULE_DIR_ . 'videodesk/models/VideodeskConfiguration.class.php');
require_once(_PS_MODULE_DIR_ . 'videodesk/models/VideodeskHistoric.class.php');
require_once(_PS_MODULE_DIR_ . 'videodesk/models/VideodeskCall.class.php');
require_once(_PS_MODULE_DIR_ . 'videodesk/models/VideodeskShopConfigurationCriteria.class.php');
require_once(_PS_MODULE_DIR_ . 'videodesk/models/VideodeskShopConfigurationCriteriaValue.class.php');
require_once(_PS_MODULE_DIR_ . 'videodesk/models/VideodeskShopConfigurationGroupPages.class.php');
require_once(_PS_MODULE_DIR_ . 'videodesk/models/VideodeskShopConfigurationPage.class.php');
require_once(_PS_MODULE_DIR_ . 'videodesk/models/VideodeskShopConfigurationPageValue.class.php');
require_once(_PS_MODULE_DIR_ . 'videodesk/lib/Http/Connector.php');

class Videodesk15 extends Module
{
	// Module download source (PrestaShop => VDPSP / Videodesk => VDCOM), set the main source in first position
	// public $_source = array('VDPSP', 'VDCOM');
	public $_source = array('VDCOM');
	// Main Videodesk configuration URL 
	public $_videodesk_main_url = 'http://api.videodesk.com/affiliate/parameters/';
	// Module platform
	public $_platform = 'prestashop';
	private $_token;

	public $_timeout = 0;

	/**
	 * @see ModuleCore::__construct()
	 */
	public function __construct()
	{
		$this->name = 'videodesk';
		$this->tab = 'front_office_features';
		$this->version = '2.2';
		$this->module_key = '9f78a6239ec9f2731c8fc97e620cb485';
		$this->author = 'Videodesk';
		$this->need_instance = 1;

		// Bootstrap update v1.6.x
		$this->bootstrap = true;

		parent::__construct();
		
		$this->displayName = $this->l('videodesk');
		
		// Rich description with a Read More link to videodesk
		$description = $this->l('Offer face-to-face customer service on your website via text, audio or video chat');
		$readmore_link = '';
		$readmore_found = false;
		if ($readmore_link = Configuration::getGlobalValue('VD_READMORE')) {
			$readmore_found = true;
		}
		else {
			$response = HttpRequester::post($this->_videodesk_main_url . "?lang=" . $this->context->language->iso_code . "&affiliate=" . $this->module_key, $this->getConfPost());
			if ($response->getStatus() == "200") {
				$content = $response->asJson();
				$readmore_link = $content->config->readmore_url;
				Configuration::updateGlobalValue('VD_READMORE', $readmore_link);
				$readmore_found = true;
			}
		}
		if ($readmore_found) {
			$description .= " <a href='" . $readmore_link . "?lang=" . $this->context->language->iso_code . "' target='_blank' class='action_module'>" . $this->l('Read more') . "</a>";
		}
		$this->description = $description;
		$this->ps_versions_compliancy['min'] = '1.5.0.1';

		$this->confirmUninstall = $this->l('Are you sure you want to delete this module ?');

		$this->_token = Configuration::getGlobalValue('VD_ACCESS_TOKEN');
		if ($this->_token === false) {
			$this->_token = Tools::encrypt(Tools::passwdGen(32));
			Configuration::updateGlobalValue('VD_ACCESS_TOKEN', $this->_token);
		}

		if (Configuration::getGlobalValue('VD_CONNECTION_SECURED') == '0')
			$this->warning = $this->l('curl extension is not available: you won\'t be able to sign up in the module and get chat historic in your Back Office');
	}
	
	/**
	 * Install the module : configuration, database, tabs, hooks
	 * @return boolean result
	 * @see ModuleCore::install()
	 */
	public function install()
	{
		/**
		 * Configuration
		 */
		$configuration = new VideodeskConfiguration($this);
		if (!$configuration->updateConfiguration()) {
			$this->_errors = array_merge($this->_errors, $configuration->getErrors());
			return false;
		}
		Configuration::updateGlobalValue('VD_ENDPOINT', $this->_videodesk_main_url);
		Configuration::updateGlobalValue('VD_CONF_STATE', 'init');
		Configuration::updateGlobalValue('VD_HIST_LAST_CHECK', 0);
		Configuration::updateGlobalValue('VD_HIST_CHECK_INTERVAL', 5);
		Configuration::updateGlobalValue('VD_HIST_NB_LINES', 0);
		
		/**
		 * Check cURL activation
		 */
		if (extension_loaded('curl')) {
			Configuration::updateGlobalValue('VD_CONNECTION_SECURED', 1);
		}
		else {
			Configuration::updateGlobalValue('VD_CONNECTION_SECURED', 0);
		}
		
		/**
		 * SQL tasks
		 */
		$sql = array();
		include(dirname(__FILE__) . '/sql/install.php');
		foreach ($sql as $s) {
			if (!Db::getInstance()->execute($s)) {
				$this->_errors[] = 'SQL problem: ' . $s;
				return false;
			}
		}
		
		/**
		 * Tabs
		 */
		//Parent Tab
		if ($id_tab_parent = Tab::getIdFromClassName('AdminOrders'))
			$parent_tab = new Tab($id_tab_parent);
		else
			$parent_tab = new Tab(1);
		
		// Module Tab
		$languages = Language::getLanguages(false);
		$tab = new Tab();
		foreach ($languages as $lang)
			$tab->name[$lang['id_lang']] = 'Videodesk';
		$tab->class_name = 'AdminVideodeskCall';
		$tab->id_parent = $parent_tab->id;
		$tab->module = $this->name;
		if (!$tab->add()) {
			$this->_errors[] = 'Tab creation';
			return false;
		}
		
		/**
		 * Parent tasks & Enable on all shops & hooks
		 */
		if (
			!parent::install() || 
			!$this->registerHook('displayFooter') || 
			!$this->registerHook('displayAdminOrder') || 
			!$this->registerHook('displayAdminCustomers') || 
			!$this->registerHook('displayAdminStatsModules') || 
			!$this->registerHook('displayBackOfficeHeader') || 
			!$this->registerHook('displayBackOfficeTop') || 
			!$this->registerHook('actionValidateOrder')
		) {
			return false;
		}
		
		/**
		 * Enable the module on all active shops - default PrestaShop behavior doesn't work
		 * @see ModuleCore::enable()
		 */
		// Retrieve all shops where the module is enabled
		$list = Shop::getShops(true, null, true);
		$sql = 'SELECT `id_shop` FROM `' . _DB_PREFIX_ . 'module_shop`
				WHERE `id_module` = ' . $this->id . ' AND `id_shop` IN(' . implode(', ', $list) . ')';
		
		// Store the results in an array
		$items = array();
		if ($results = Db::getInstance($sql)->executeS($sql))
			foreach ($results as $row)
				$items[] = $row['id_shop'];
		
		// Enable module in the shop where it is not enabled yet
		foreach ($list as $id)
			if (!in_array($id, $items))
				Db::getInstance()->insert('module_shop', array(
					'id_module' => $this->id,
					'id_shop' => $id
				));
		
		/**
		 * Send installation notification
		 */
		$configuration->sendInstallNotification();
		
		return true;
	}
	
	/**
	 * Uninstall the module : configuration, database, tabs, hooks
	 * @return boolean result
	 * @see ModuleCore::uninstall()
	 */
	public function uninstall()
	{
		/**
		 * SQL tasks
		 */
		$sql = array();
		include(dirname(__FILE__) . '/sql/uninstall.php');
		foreach ($sql as $s) {
			if (!Db::getInstance()->execute($s)) {
				$this->_errors[] = 'SQL problem: ' . $s;
				return false;
			}
		}
		
		/**
		 * Configuration
		 */
		Configuration::deleteByName('VD_ENDPOINT');
		Configuration::deleteByName('VD_NOTIF_VDCOM');
		Configuration::deleteByName('VD_NOTIF_VDPSP');
		Configuration::deleteByName('VD_READMORE');
		Configuration::deleteByName('VD_PRICING');
		Configuration::deleteByName('VD_CGU');
		Configuration::deleteByName('VD_EDITO_URL');
		Configuration::deleteByName('VD_EDITO_WIDTH');
		Configuration::deleteByName('VD_EDITO_HEIGHT');
		Configuration::deleteByName('VD_HELP_SIGNUP_URL');
		Configuration::deleteByName('VD_HELP_SIGNUP_WIDTH');
		Configuration::deleteByName('VD_HELP_SIGNUP_HEIGHT');
		Configuration::deleteByName('VD_BO_MODULE_JS');
		Configuration::deleteByName('VD_PREVIEW_URL');
		Configuration::deleteByName('VD_BO_HOME');
		Configuration::deleteByName('VD_BO_TEMPLATE');
		Configuration::deleteByName('VD_BO_TEXTS');
		Configuration::deleteByName('VD_BO_MESSAGES');
		Configuration::deleteByName('VD_BO_AGENT');
		
		Configuration::deleteByName('VD_EMAIL_CHECK');
		Configuration::deleteByName('VD_FORM_SIGNIN');
		Configuration::deleteByName('VD_FORM_SIGNUP_ACCOUNT');
		Configuration::deleteByName('VD_FORM_SIGNUP_SHOP');
		Configuration::deleteByName('VD_FORM_SIGNUP_ACCOUNT_SUB');
		Configuration::deleteByName('VD_FORM_SIGNUP_SHOP_SUB');
		Configuration::deleteByName('VD_FORM_SIGNUP_AGENT');
		Configuration::deleteByName('VD_FORM_SIGNUP_AGENT_SUB');
		Configuration::deleteByName('VD_FORM_SIGNUP_SHOP_AGENT');
		Configuration::deleteByName('VD_FORM_SIGNUP_SHOP_AGENT_SUB');
		
		Configuration::deleteByName('VD_NOTIF_OBJECTIVE');
		
		Configuration::deleteByName('VD_ACCOUNT_UID');
		Configuration::deleteByName('VD_ACCOUNT_UID_AGENT');
		Configuration::deleteByName('VD_CONF_STATE');
		Configuration::deleteByName('VD_CONNECTION_SECURED');
		Configuration::deleteByName('VD_ACCESS_TOKEN');
		
		// Historic configuration
		Configuration::deleteByName('VD_HIST_LAST_CHECK');
		Configuration::deleteByName('VD_HIST_CHECK_INTERVAL');
		Configuration::deleteByName('VD_HIST_NB_LINES');
		
		/**
		 * Tabs
		 */
		$tab = new Tab(Tab::getIdFromClassName('AdminVideodeskCall'));
		if (!$tab->delete()) {
			$this->_errors[] = 'Tab deletion';
			return false;
		}
		
		/**
		 * Parent tasks & hooks
		 */
		if (
			!parent::uninstall() || 
			!$this->unregisterHook('displayFooter') || 
			!$this->unregisterHook('displayAdminOrder') || 
			!$this->unregisterHook('displayAdminCustomers') || 
			!$this->unregisterHook('displayAdminStatsModules') || 
			!$this->unregisterHook('displayBackOfficeHeader') || 
			!$this->unregisterHook('displayBackOfficeTop') || 
			!$this->unregisterHook('actionValidateOrder')
		) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Display content (or process action) when accessing the configuration of the module
	 * @return HTML content
	 */
	public function getContent()
	{
		$configuration = new VideodeskConfiguration($this);
		
		// Sign In process
		if (Tools::isSubmit('submitSignIn')) {
			// Test if the install notification has been sent
			$configuration->sendInstallNotification();
			
			if (!$configuration->processSignIn()) {
				$this->_errors = array_merge($this->_errors, $configuration->getErrors());
			}
			if (Configuration::getGlobalValue('VD_CONF_STATE') == 'init') {
				$configuration->processDisplayHome();
			} else {
				$configuration->processDisplayConfiguration();
			}
		}
		// Sign Up process
		elseif (Tools::isSubmit('submitSignUp')) {
			// Test if the install notification has been sent
			$configuration->sendInstallNotification();
			
			// If secured connection is available => Sign Up in the module
			if (Configuration::getGlobalValue('VD_CONNECTION_SECURED') == '1') {
				$configuration->processDisplaySignUpStep1();
			}
			// Else => Redirect to the Videodesk website
			else {
				$redirect_url = Configuration::getGlobalValue('VD_BO_HOME');
				Tools::redirect($redirect_url);
			}
		}
		// Sign Up process => Step 3
		elseif (Tools::isSubmit('submitSignUp_Step3')) {
			$configuration->processDisplayConfiguration();
		}
		// Sign Up process => Step 2
		elseif (Tools::isSubmit('submitSignUp_Step2')) {
			$configuration->processDisplaySignUpStep3();
		}
		// Sign Up process => Step 1
		elseif (Tools::isSubmit('submitSignUp_Step1')) {
			$configuration->processDisplaySignUpStep2();
		}
		// Advanced configuration
		elseif (Tools::isSubmit('shopConfiguration')) {
			$configuration->processDisplayShopConfiguration();
		}
		// Advanced configuration post process
		elseif (Tools::isSubmit('submitShopConfiguration')) {
			$configuration->processUpdateShopConfiguration();
			$this->_errors = array_merge($this->_errors, $configuration->getErrors());
		}
		// Common display
		else {
			if (Configuration::getGlobalValue('VD_CONF_STATE') == 'init') {
				$configuration->processDisplayHome();
			}
			else {
				$configuration->processDisplayConfiguration();
			}
		}
		
		// Handle display : add CSS and needed JS
		if ($configuration->display()) {
			// Add default CSS
			$this->context->controller->addCSS($this->getPathUri() . 'views/css/videodesk.css', 'all');
			
			// Add dform JS for pages with form
			if ($configuration->needForm()) {
				$this->context->controller->addJS($this->getPathUri() . 'views/js/jquery.dform-1.0.1.min.js');
			}
			
			// Add cluetip JS & CSS for infobulle
			$this->context->controller->addCSS($this->getPathUri() . 'views/css/jquery.cluetip.css');
			$this->context->controller->addJS($this->getPathUri() . 'views/js/jquery.cluetip.js');

			$this->context->smarty->assign(array(
				'request_uri' => Tools::safeOutput($_SERVER['REQUEST_URI']),
				'module_help' => _PS_MODULE_DIR_ . $this->name . '/views/templates/admin/module_help.tpl',
				'lang_iso' => $this->context->language->iso_code,
				'errors' => $this->_errors
			));
			
			return $this->display(dirname(__FILE__), '/views/templates/admin/' . $configuration->getTemplate() . '.tpl');
		}
		
		return '';
	}
	
	/**
	 * Display Videodesk Historic on an Order detail
	 * @return HTML content
	 */
	public function hookDisplayAdminOrder($params)
	{
		$order = new Order(Tools::getValue('id_order'));
		if (($calls = VideodeskCall::getCallsFromOrder($order->id_cart, $this->context->shop->id)) !== false) {
			$this->context->smarty->assign(array(
				'calls' => $calls,
				'type' => 'order',
				'token' => Configuration::getGlobalValue('VD_ACCESS_TOKEN'),
				'isMultishop' => (!Shop::isFeatureActive() || Shop::getTotalShops(false, null) < 2) ? false : true
			));
			
			$this->context->controller->addJS($this->getPathUri() . 'views/js/bo_call.js');
			$this->context->controller->addCSS($this->getPathUri() . 'views/css/bo_call.css');
			
			return $this->display(dirname(__FILE__), '/views/templates/admin/bo-calls.tpl');
		}
	}
	
	/**
	 * Display Videodesk Historic on a Customer detail
	 * @return HTML content
	 */
	public function hookDisplayAdminCustomers($params)
	{
		if (($calls = VideodeskCall::getCallsFromCustomer(Tools::getValue('id_customer'), $this->context->shop->id)) !== false) {
			$this->context->smarty->assign(array(
				'calls' => $calls,
				'type' => 'customer',
				'token' => Configuration::getGlobalValue('VD_ACCESS_TOKEN'),
				'isMultishop' => (!Shop::isFeatureActive() || Shop::getTotalShops(false, null) < 2) ? false : true
			));
			
			$this->context->controller->addJS($this->getPathUri() . 'views/js/bo_call.js');
			$this->context->controller->addCSS($this->getPathUri() . 'views/css/bo_call.css');
			
			return $this->display(dirname(__FILE__), '/views/templates/admin/bo-calls.tpl');
		}
	}
	
	/**
	 * Display Videodesk Historic on the Statistics page
	 * @return HTML content
	 */
	public function hookDisplayAdminStatsModules($params)
	{
		$date_interval = ModuleGraph::getDateBetween();
		
		$nb_calls = VideodeskCall::statsGetNbCalls($date_interval);
		
		if ($nb_calls > 0) {			
			// Get number of calls by Type
			$nb_calls_by_type = VideodeskCall::statsGetNbCallsByType($date_interval);
			
			// Get number of calls by Employee
			$nb_calls_by_employee = VideodeskCall::statsGetNbCallsByEmployee($date_interval);
			foreach ($nb_calls_by_employee as &$employee_call) {
				$employee = new Employee($employee_call['id_employee']);
				$employee_call['employee_name'] = $employee->firstname . ' ' . $employee->lastname;
			}
			
			// Get number of visitors
			$nb_visitors = VideodeskCall::statsGetTotalGuests($date_interval);
			$nb_visitors_with_call = VideodeskCall::statsGetGuestsCalls($date_interval);
			
			// Get calls conversion
			$nb_calls_conversion = VideodeskCall::statsGetCallsConversion($date_interval);
			
			$this->context->smarty->assign(array(
				'nb_visitors' => $nb_visitors,
				'nb_visitors_with_call' => $nb_visitors_with_call,
				'nb_calls_by_type' => $nb_calls_by_type,
				'nb_calls_by_employee' => $nb_calls_by_employee,
				'nb_calls_conversion' => $nb_calls_conversion
			));
		}
		
		$this->context->smarty->assign(array(
			'module_name' => $this->name,
			'nb_calls' => $nb_calls
		));
		
		return $this->display(dirname(__FILE__), '/views/templates/admin/stats.tpl');
	}
	
	/**
	 * Process the Historic integration on each access to a Back Office page (with a configurable interval of time)
	 * Integrates the Videodesk Back in Back on all BO pages except AdminModules
	 */
	public function hookBackOfficeTop($params)
	{
		$last_check = intval(Configuration::getGlobalValue('VD_HIST_LAST_CHECK'));
		$check_interval = (intval(Configuration::getGlobalValue('VD_HIST_CHECK_INTERVAL')) * 60);
		
		if (($last_check + $check_interval) < time()) {
			$historic = new VideodeskHistoric($this);
			if ($historic->processHistoric()) {
				Configuration::updateGlobalValue('VD_HIST_LAST_CHECK', time());
				$this->context->smarty->assign(array(
					'errors' => false
				));
			} else {
				$this->context->smarty->assign(array(
					'errors' => true
				));
				
				return $this->display(dirname(__FILE__), '/views/templates/admin/bo-top.tpl');
			}
		}
		
		// Display Videodesk Back in Back 
		if ($this->context->controller->controller_name != 'AdminModules') {
			$employee = $this->context->employee;
			$this->context->smarty->assign(array(
				'employee_firstname' => $employee->firstname,
				'employee_lastname' => $employee->lastname,
				'employee_email' => $employee->email,
				'lang_iso' => $this->context->language->iso_code,
				'module_version' => $this->version,
				'js_url' => Configuration::getGlobalValue('VD_BO_MODULE_JS'),
			));
			return $this->display(dirname(__FILE__), '/views/templates/admin/bo-videodesk.tpl');
		}
	}
	
	/**
	 * Display the Videodesk JavaScript on Front Office in Footer (default hook)
	 * @return HTML content
	 */
	public function hookDisplayFooter($params)
	{
		$sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'videodesk_shop_configuration` WHERE `id_shop` = ' . $this->context->shop->id;
		$config = DB::getInstance()->getRow($sql);
		if (!empty($config['displayed']) && $config['displayed'] == true) {
			$timeout = false;
			$display = false;
			$current_ip = Tools::getRemoteAddr();
			if (!empty($config['website_id'])) {
				//display by configuration
				if ($config['display_for_all']) {
					$display = true;
				} else {
					$ips = explode(';', $config['display_ips']);
					if (!empty($ips)) {
						foreach ($ips as $key => $ip) {
							if ($ip == $current_ip) {
								$display = true;
							}
						}
					}
				}
				
				// Display with criterias
				if ($display && $config['criterias']) {
					$display = $this->checkCriterias($config['criterias_all_conditions']);
				}
				
				// Display by page
				if ($display && $config['scope']) {
					$display = $this->checkScope();
				}
				
				// Retrieve customer object
				if ($this->context->customer->isLogged(true)) {
					$customer = $this->context->customer;
				}
				
				// Retrieve cart object
				if (!empty($this->context->cart->id)) {
					$cart_id = $this->context->cart->id;
				}
				
				$this->context->smarty->assign(array(
					'website_id' => $config['website_id'],
					'customer_id' => isset($customer) ? $customer->id : '',
					'customer_firstname' => isset($customer) ? $customer->firstname : '',
					'customer_lastname' => isset($customer) ? $customer->lastname : '',
					'customer_email' => isset($customer) ? $customer->email : '',
					'customer_company' => isset($customer) ? $customer->company : '',
					'cart_id' => isset($cart_id) ? $cart_id : '',
					'display' => ($display == true ? 'on' : 'off'),
					'timeout' => ((int) $this->_timeout > 0 ? (int) $this->_timeout : false),
					'lang_iso' => $this->context->language->iso_code,
					'module_version' => $this->version
				));
				
				return $this->display(dirname(__FILE__), 'front.tpl');
			}
		}
	}
	
	/**
	 * Display the Videodesk JavaScript on Front Office in Top (secondary hook)
	 * @return HTML content
	 */
	public function hookDisplayTop($params)
	{
		return $this->hookDisplayFooter($params);
	}
	
	/**
	 * Display the Videodesk JavaScript on Front Office in Right Column (secondary hook)
	 * @return HTML content
	 */
	public function hookDisplayRightColumn($params)
	{
		return $this->hookDisplayFooter($params);
	}
	
	/**
	 * Display the Videodesk JavaScript on Front Office in Left Column (secondary hook)
	 * @return HTML content
	 */
	public function hookDisplayLeftColumn($params)
	{
		return $this->hookDisplayFooter($params);
	}
	
	/**
	 * Send statistics to Videodesk on Order validation
	 */
	public function hookActionValidateOrder($params)
	{
		$sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'videodesk_shop_configuration` WHERE `id_shop` = ' . $this->context->shop->id;
		$config = DB::getInstance()->getRow($sql);
		if (!empty($config['track_stats']) && $config['track_stats'] == true) {
			$order = $params['order'];
			$currency = $params['currency'];
			$response = HttpRequester::get(
					Configuration::getGlobalValue('VD_NOTIF_OBJECTIVE').
					'?website_id='.$config['website_id'].
					'&cart_id='.$order->id_cart.
					'&goal_label=Order'.
					'&goal_value='.$order->total_paid.
					'&goal_unit='.$currency->iso_code
			);
		}
	}
	
	/**
	 * Check criterias for Front Office display
	 * 
	 * @param int $criterias_all_conditions
	 * @return boolean
	 */
	public function checkCriterias($criterias_all_conditions = 0)
	{
		$display = false;
		$test = array();
		$sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'videodesk_shop_configuration_criteria_value` AS crv
        	JOIN `' . _DB_PREFIX_ . 'videodesk_shop_configuration_criteria` AS cr ON cr.`id_criteria` = crv.`id_criteria`
        	WHERE `id_shop` = ' . $this->context->shop->id . ' AND `active` = 1';
		$criterias = DB::getInstance()->executeS($sql);
		if (!empty($criterias)) {
			foreach ($criterias as $key => $criteria) {
				if (((int) $criteria['with_value'] == 1 && !empty($criteria['value'])) || ((int) $criteria['with_value'] == 0 && empty($criteria['value']))) {
					if ($criteria['name'] == 'visitor_logged') {
						if ($this->context->customer->isLogged() == true) {
							$test[$criteria['name']] = $criteria['value'];
						}
					}
					else if ($criteria['name'] == 'seconds') {
						$this->_timeout = $criteria['value'];
						if ($criterias_all_conditions == 1) {
							unset($criterias[$key]);
						} else {
							$test[$criteria['name']] = $criteria['value'];
						}
					}
					else if ($criteria['name'] == 'cart_amount') {
						if ($this->context->cart->getOrderTotal() > $criteria['value']) {
							$test[$criteria['name']] = $criteria['value'];
						}
					}
					else if ($criteria['name'] == 'product_cart_amount') {
						foreach ($this->context->cart->getProducts() as $key => $product) {
							if ($product['total_wt'] > $criteria['value']) {
								$test[$criteria['name']] = $criteria['value'];
							}
						}
					}
					else if ($criteria['name'] == 'customer_nb_returning') {
						$ip_formated = ip2long(Tools::getRemoteAddr());
						$sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'connections AS c WHERE c.ip_address = "' . $ip_formated . '" GROUP BY DATE_FORMAT(c.date_add, "%Y%m%d%")';
						$result = Db::getInstance()->executeS($sql);
						if (sizeof($result) > $criteria['value']) {
							$test[$criteria['name']] = $criteria['value'];
						}
					}
					else {
						$test[$criteria['name']] = $criteria['value'];
					}
				}
			}
			
			if ($criterias_all_conditions == 1) {
				if (sizeof($test) == sizeof($criterias)) {
					$display = true;
				}
			}
			else {
				if (sizeof($test) > 0) {
					$display = true;
				}
			}
		}
		return $display;
	}
	
	/**
	 * Check scope for Front Office display
	 * 
	 * @return boolean
	 */
	public function checkScope()
	{
		$display = false;
		if (isset($this->context->getContext()->controller->page_name)) {
			$controller = $this->context->getContext()->controller->page_name;
		} else {
			$controller = Dispatcher::getInstance()->getController();
		}
		if (!empty($this->context->controller->module)) {
			$controller = 'module-' . $this->context->controller->module->name . '-' . $controller;
		}
		$condition = '';
		/*if ($controller == 'index') {
		$condition = ' AND (cp.`name` = "'.Db::getInstance()->escape($this->context->shop->getCategory()).'" AND cgp.`name` = "categories")';
		}
		else */
		if ($controller == 'cms') {
			$condition = ' AND (cp.`name` = "' . Db::getInstance()->escape(Tools::getValue('id_cms')) . '" AND cgp.`name` = "cms")';
		}
		else if ($controller == 'category') {
			$condition = ' AND (cp.`name` = "' . Db::getInstance()->escape(Tools::getValue('id_category')) . '" AND cgp.`name` = "categories")';
		}
		else if ($controller == 'product') {
			$product = new Product(Tools::getValue('id_product'));
			$condition = ' AND (cp.`name` = "' . Db::getInstance()->escape($product->id_category_default) . '" AND cgp.`name` = "categories")';
		}
		else if ($controller == 'modules') {
			$condition = ' AND (cp.`name` = "' . Db::getInstance()->escape(Tools::getValue('id_cms')) . '" AND cgp.`name` = "modules")';
		}
		else {
			$condition = ' AND (cp.`name` = "' . Db::getInstance()->escape($controller) . '")';
		}
		$sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'videodesk_shop_configuration_page_value` AS cpv
        	JOIN `' . _DB_PREFIX_ . 'videodesk_shop_configuration_page` AS cp ON cp.`id_page` = cpv.`id_page`
        	LEFT JOIN `' . _DB_PREFIX_ . 'videodesk_shop_configuration_group_pages` AS cgp ON cgp.`id_group_pages` = cp.`id_group_pages`
			WHERE cpv.`id_shop` = ' . $this->context->shop->id . $condition;
		$pages = DB::getInstance()->executeS($sql);
		if (!empty($pages) && sizeof($pages) > 0) {
			$display = true;
		}
		return $display;
	}
	
	/**
	 * Set the standard datas as an array for POST requests
	 * 
	 * @return array
	 */
	public function getConfPost()
	{
		return array(
			'conf' => array(
				'lang' => $this->context->language->iso_code,
				'key' => $this->module_key,
				'platform' => $this->_platform,
				'version' => $this->version,
				'affiliate' => $this->_source[0],
			)
		);
	}

	public function getToken()
	{
		return $this->_token;
	}

	public function hookDisplayBackOfficeHeader($params)
	{
	}
}
