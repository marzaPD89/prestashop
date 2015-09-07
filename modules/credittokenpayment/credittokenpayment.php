<?php
	
	class CreditTokenPayment extends Module	{

		public function __construct()	{

			$this->name = 'credittokenpayment';
			$this->version = '0.1';
			$this->author = 'Luca Marzaro';
			$this->displayName = 'Token payment module';
			$this->description = 'With this module you\'ll be able to buy token credits to buy real products'; 
			$this->bootstrap = true;
			parent::__construct();

		}

		public function install()	{

			if(!parent::install())
				return false;

			$sql_file = dirname(__FILE__).'/install/install.sql';
			if(!$this->loadSQLFile($sql_file))
				return false;

			return true;

		}

		public function loadSQLFile($sql_file) {

			$sql_content = file_get_contents($sql_file);

			$sql_content = str_replace('PREFIX_', '_DB_PREFIX_', $sql_content);
			$sql_requests = preg_split("/;\s*[\r\n]+/", $sql_content);

			$result = true;
			foreach ($sql_requests as $request) {
				if(!empty($request))
					$result &= Db::getInstance()->execute(trim($request));
			}

			return $result;

		}

		public function initAdmin()	{

			$sql = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'credittokenpayment_configuration`');
			if(!empty($sql))
				$this->context->smarty->assign('queryInit', $sql);
		
		}

		public function processConfiguration()	{

			if(Tools::isSubmit('submit-configuration')) {

				$all_post = $_POST;
				$match_array[] = array();
				$conta = 0;
				foreach($all_post as $key => $post) {

					$patternPrd = '/^(products_)([0-9]+)/';
					$patternCredit = '/^(credit_products_)([0-9]+)/';
					preg_match($patternPrd, $key, $matchesPrd);
					preg_match($patternCredit, $key, $matchesCredit);
					
					if(!empty($matchesPrd)) {
						
						$prdValue = Tools::getValue($matchesPrd[0]);
						$match_array[$conta]['id_row'] = (int)$matchesPrd[2];
						$match_array[$conta]['id_product'] = (int)$prdValue;

					}

					if(!empty($matchesCredit))	{
						
						$creditValue = Tools::getValue($matchesCredit[0]);
						$match_array[$conta++]['credits'] = (int)$creditValue;

					}


					/*$sql = Db::getInstance()->executeS('SELECT `id_feature_value` FROM `'._DB_PREFIX_.'feature_value_lang` WHERE `value` = \''.pSQL($featureValue).'\'');

					
					if(!empty($featureValue) && !$sql) {

						Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'feature_value_lang` VALUES (DEFAULT, 1, '.$featureValue.')');

						$verifyIfFeatureValue = Db::getInstance()->executeS('SELECT `id_feature_value` FROM `'._DB_PREFIX_.'feature_value_lang` WHERE `value` = \''.pSQL($featureValue).'\'');

						if($verifyIfFeatureValue)
							Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'feature_value` VALUES ('.(int)$verifyIfFeatureValue['id_feature_value'].','.(int)$matches[2].', 0)');

					}*/


				}
				
				foreach($match_array as $match) {

					if($match['id_product'] != 0 && $match['credits'] != 0)
						Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'credittokenpayment_configuration` VALUES ('.$match['id_row'].','.$match['id_product'].', '.$match['credits'].') ON DUPLICATE KEY UPDATE credits='.$match['credits'].'');

				}

				$this->initAdmin();
				$this->context->smarty->assign('idprd',true);

			}

		}

		public function getContent()	{

			$this->processConfiguration();

			$this->context->controller->addCSS($this->_path.'views/css/credittokenpayment.css', 'all');
			$this->context->controller->addJS($this->_path.'views/js/credittokenpayment.js');

			$this->context->smarty->assign('base_dir',_PS_BASE_URL_.__PS_BASE_URI__);

			return $this->display(__FILE__, 'getContent.tpl');

		}

	}

?>