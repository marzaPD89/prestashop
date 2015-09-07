<?php
if (!defined('_PS_VERSION_'))
	exit;

class PCollegati extends Module
{
	private $image_suffix = '';

	public function __construct()
	{
		$this->name = 'pcollegati';
		$this->tab = 'front_office_features';
		$this->version = '1.9.4';
		$this->author = 'LMarzaro';
		$this->need_instance = 0;
		$this->module_key = 'cad0aaa2bb2946b5192be6858q88675a';

		parent::__construct();
		$this->registerHook('header');

		$this->displayName = $this->l('Prodotti collegati');
		$this->description = $this->l('mostra i prodotti collegati');

		if (version_compare(_PS_VERSION_,'1.5','>')) {
			$this->image_suffix = '_default';
		}

		//$this->registerHook('displayRightColumnProduct');
	}

	public function install()
	{
		Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'pcollegati_products` (
				`id_product` INT UNSIGNED NOT NULL,
				`id_product_related` INT UNSIGNED NOT NULL,
				PRIMARY KEY (`id_product`, `id_product_related`)
			)'
		);
		$res = parent::install();

		if (version_compare(_PS_VERSION_,'1.5','>')) {
			$res &= $this->registerHook('displayAdminProductsExtra');
			$res &= $this->registerHook('displayVarianti');
		}
		$res &= $this->registerHook('header');
		//$res &= $this->registerHook('productFooter');
		$res &= $this->registerHook('rightColumn');
		$res &= $this->registerHook('backOfficeHeader');

		Configuration::updateValue('PCORRL_TWO_WAY', 1);
		Configuration::updateValue('PCORRL_NB_PRODUCTS', 30);
		Configuration::updateValue('PCORRL_DISPLAY_REDUCTION', 1);
		Configuration::updateValue('PCORRL_DISPLAY_PRICE', 1);
		Configuration::updateValue('PCORRL_DISPLAY_NAME', 1);
		Configuration::updateValue('PCORRL_DISPLAY_BUY', 1);

		return $res;
	}

	public function uninstall()
	{
		//Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pcollegati_products');
		//Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pcollegati_products_stats');

		Configuration::deleteByName('PCORRL_TWO_WAY');
		Configuration::deleteByName('PCORRL_NB_PRODUCTS');
		Configuration::deleteByName('PCORRL_DISPLAY_REDUCTION');
		Configuration::deleteByName('PCORRL_DISPLAY_PRICE');
		Configuration::deleteByName('PCORRL_DISPLAY_NAME');
		Configuration::deleteByName('PCORRL_DISPLAY_BUY');
		return parent::uninstall();
	}
	
	public function hookBackOfficeHeader($params)
	{
		if (version_compare(_PS_VERSION_,'1.5','>')) {
			$shopId = Context::getContext()->shop->id;
		} else {
			$shopId = 0;
		}

		return '
			<script type="text/javascript" src="' . __PS_BASE_URI__ . 'modules/pcollegati/js/admin.js"></script>
			<script type="text/javascript">
				COLLEGATI_AJAX_URL = \'' . __PS_BASE_URI__ . 'modules/pcollegati/ajax-back.php?id_shop=' . $shopId . '&callback=?\';
				COLLEGATI_TOKEN = \'' . Tools::substr(Tools::encrypt('relatedproducts/back'),0,10) . '\';
				ID_PRODUCT = \'' . Tools::getValue('id_product') . '\';
			</script>';
	}

	public function hookDisplayAdminProductsExtra($params)
	{
		return '<script type="text/javascript">loadRelatedProductsPage2();</script>';
	}

	public function hookProductFooter($params)
	{
		$cookie = Context::getContext()->cookie;
		$smarty = Context::getContext()->smarty;

		$nbProducts = Configuration::get('PCORRL_NB_PRODUCTS');

		$relatedProductsRes = Db::getInstance()->executeS('
			SELECT `id_product_related`
			FROM `' . _DB_PREFIX_ . 'pcollegati_products` apr
			INNER JOIN `' . _DB_PREFIX_ . 'product` p
				ON p.id_product = apr.id_product_related
			WHERE p.active = 1 AND apr.id_product = ' . (int)Tools::getValue('id_product'));

		$relatedProducts = array();
		if ($relatedProductsRes) {
			foreach ($relatedProductsRes as $relatedProductRes) {
				$product = new Product($relatedProductRes['id_product_related'],  null, $cookie->id_lang);
				$cover  = Product::getCover($relatedProductRes['id_product_related']);
				$product->id_image =  $product->id . '-' . $cover['id_image'];
				$relatedProducts[] = $product;
				$priceDisplay = Product::getTaxCalculationMethod();
				if ($priceDisplay == 1) {
					$product->price = $product->getPrice(false);
					$product->priceWhitoutReduction = $product->getPriceWithoutReduct(true, null);
				} else {
					$product->price = $product->getPrice(true);
					$product->priceWhitoutReduction = $product->getPriceWithoutReduct(false, null);
				}

				if(Configuration::get('PS_REWRITING_SETTINGS'))
					$product->link_complement = '?ref=relatedproducts&from='.(int)Tools::getValue('id_product');
				else
					$product->link_complement = '&ref=relatedproducts&from='.(int)Tools::getValue('id_product');

				$product->addToCartUrl = __PS_BASE_URI__ . 'index.php?controller=cart&id_product=' . $product->id . '&add&ref=relatedproducts&from='.(int)Tools::getValue('id_product');
				if (version_compare(_PS_VERSION_,'1.5','>')) {
					if (isset(Context::getContext()->customer) AND Context::getContext()->customer->id)
						$product->addToCartUrl .= '&token=' . Tools::getToken(false);
				} else
					if ($cookie->isLogged())
						$product->addToCartUrl .= '&token=' . Tools::getToken(false);
			}
		}
		$smarty->assign('relatedProducts', $relatedProducts);

		$smarty->assign('displayReduction', Configuration::get('PCORRL_DISPLAY_REDUCTION'));
		$smarty->assign('displayPrice', Configuration::get('PCORRL_DISPLAY_PRICE'));
		$smarty->assign('displayName', Configuration::get('PCORRL_DISPLAY_NAME'));
		$smarty->assign('displayBuy', Configuration::get('PCORRL_DISPLAY_BUY'));

		$smarty->assign('imageSuffix', $this->image_suffix);

		return $this->display(__FILE__, 'product.tpl');
	}

	public function hookDisplayRightColumn($params){
		
	}

	public function  hookDisplayRightColumnProduct($params){
		return $this->hookProductFooter($params);
	}

	public function  hookDisplayVarianti($params){
		return $this->hookProductFooter($params);
	}


	public function getContent()
	{
		$smarty = Context::getContext()->smarty;

		$limit = 1;

		if (Tools::isSubmit('submitSettings'))
		{
			Configuration::updateValue('PCORRL_TWO_WAY', (int)Tools::getValue('madef_advanced_related_two_way'));
			Configuration::updateValue('PCORRL_NB_PRODUCTS', (int)Tools::getValue('madef_advanced_related_nb_products'));
			Configuration::updateValue('PCORRL_DISPLAY_REDUCTION', (int)Tools::getValue('madef_advanced_related_display_reduction'));
			Configuration::updateValue('PCORRL_DISPLAY_PRICE', (int)Tools::getValue('madef_advanced_related_display_price'));
			Configuration::updateValue('PCORRL_DISPLAY_NAME', (int)Tools::getValue('madef_advanced_related_display_name'));
			Configuration::updateValue('PCORRL_DISPLAY_BUY', (int)Tools::getValue('madef_advanced_related_display_buy'));
		} else if (Tools::getIsset('period')) {
			$limit = (int)Tools::getValue('period', 1);
		}

		$smarty->assign('period', $limit);
		$smarty->assign('twoWay', Configuration::get('PCORRL_TWO_WAY'));
		$smarty->assign('nbProducts', Configuration::get('PCORRL_NB_PRODUCTS'));
		$smarty->assign('displayReduction', Configuration::get('PCORRL_DISPLAY_REDUCTION'));
		$smarty->assign('displayPrice', Configuration::get('PCORRL_DISPLAY_PRICE'));
		$smarty->assign('displayName', Configuration::get('PCORRL_DISPLAY_NAME'));
		$smarty->assign('displayBuy', Configuration::get('PCORRL_DISPLAY_BUY'));

		$smarty->assign('stats', FALSE);
		return $smarty->fetch(_PS_MODULE_DIR_.'pcollegati/views/templates/admin/config.tpl');
	}

	public function hookHeader($params)
	{
		if (version_compare(_PS_VERSION_, '1.5', '>'))
		{
			$this->context->controller->addCSS($this->_path . 'css/related.css', 'all');
		}
		else
		{
			Tools::addCSS($this->_path . 'css/related.css', 'all');
		}
	}
}
