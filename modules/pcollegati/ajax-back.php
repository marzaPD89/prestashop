<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');


if (Tools::substr(Tools::encrypt('relatedproducts/back'),0,10) != Tools::getValue('token') || !Module::isInstalled('pcollegati'))
	die('Bad token');

include(dirname(__FILE__).'/pcollegati.php');

new ajaxBack;

class ajaxBack
{
	private $image_suffix = '';

	public function __construct()
	{
		if (version_compare(_PS_VERSION_,'1.5','>')) {
			$this->image_suffix = '_default';
		}
		switch(Tools::getValue('action')) {
			case 'template':
				$this->_display($this->_template());
				break;
			case 'productlist':
				$this->_display($this->_productList());
				break;
			case 'remove':
				$this->_display($this->_remove());
				break;
			case 'add':
				$this->_display($this->_add());
				break;
			default:
				throw new Exception('Error: Action is unknow or empty');
		}
	}

	private function _display($foo)
	{
		if (empty($foo)) {
			$foo = array('error' => false);
		}
		die(Tools::getValue('callback') . '(' . Tools::jsonEncode($foo) . ');');
	}

	private function _template()
	{
		$cookie = Context::getContext()->cookie;
		$smarty = Context::getContext()->smarty;

		$relatedProductsRes = Db::getInstance()->executeS('
			SELECT `id_product_related`
			FROM `' . _DB_PREFIX_ . 'pcollegati_products` apr
			INNER JOIN `' . _DB_PREFIX_ . 'product` p
				ON p.id_product = apr.id_product_related
			WHERE apr.id_product = ' . (int)Tools::getValue('id_product'));

		$relatedProducts = array();
		if ($relatedProductsRes) {
			foreach ($relatedProductsRes as $relatedProductRes) {
				$product = new Product($relatedProductRes['id_product_related'],  null, $cookie->id_lang);
				$cover  = Product::getCover($relatedProductRes['id_product_related']);
				$image = new Image($cover['id_image']);
				$product->img = '../img/p/' . $image->getExistingImgPath() . '-small_default.jpg';

				$relatedProducts[] = $product;
			}
		}


		$smarty->assign('relatedProducts', $relatedProducts);

		$smarty->assign('stats', false);

		return $smarty->fetch(_PS_MODULE_DIR_.'pcollegati/views/templates/admin/template.tpl');
	}

	private function _productList()
	{
		$smarty = Context::getContext()->smarty;

		$forbiddenIds = array((int)Tools::getValue('id_product'));
		$relatedProductsRes = Db::getInstance()->executeS('
			SELECT `id_product_related`
			FROM `' . _DB_PREFIX_ . 'pcollegati_products` apr
			INNER JOIN `' . _DB_PREFIX_ . 'product` p
				ON p.id_product = apr.id_product_related
			WHERE apr.id_product = ' . (int)Tools::getValue('id_product'));
		if ($relatedProductsRes) {
			foreach ($relatedProductsRes as $relatedProductRes) {
				$forbiddenIds[] = $relatedProductRes['id_product_related'];
			}
		}

		$matchedProducts = $this->_searchProductByName(Tools::getValue('search'), $forbiddenIds);

		if ($matchedProducts !== false) {
			foreach ($matchedProducts as &$product) {
				$image = new Image($product['id_image']);
				$product['img'] = '../img/p/' . $image->getExistingImgPath() . '-thickbox_default.jpg';
			}
		} else {
			$matchedProducts = array();
		}

		$smarty->assign('matchedProducts', $matchedProducts);

		return $smarty->fetch(_PS_MODULE_DIR_.'pcollegati/views/templates/admin/productlist.tpl');
	}

	private function _add()
	{
		Db::getInstance()->execute('
			INSERT INTO `' . _DB_PREFIX_ . 'pcollegati_products`
			SET
				id_product = ' . (int)Tools::getValue('id_product') . ',
				id_product_related = ' . (int)Tools::getValue('id_product_related'));
		if (Configuration::get('MADEF_ARELATED_TWO_WAY')) {
			Db::getInstance()->execute('
				INSERT INTO `' . _DB_PREFIX_ . 'pcollegati_products`
				SET
					id_product_related = ' . (int)Tools::getValue('id_product') . ',
					id_product = ' . (int)Tools::getValue('id_product_related'));
		}
		return $this->_template();
	}

	private function _remove()
	{
		Db::getInstance()->execute('
			DELETE FROM `' . _DB_PREFIX_ . 'pcollegati_products`
			WHERE id_product = ' . (int)Tools::getValue('id_product') . '
			AND id_product_related = ' . (int)Tools::getValue('id_product_related'));
		if (Configuration::get('MADEF_ARELATED_TWO_WAY')) {
			Db::getInstance()->execute('
				DELETE FROM `' . _DB_PREFIX_ . 'pcollegati_products`
				WHERE id_product_related = ' . (int)Tools::getValue('id_product') . '
				AND id_product = ' . (int)Tools::getValue('id_product_related'));
		}
		return $this->_template();
	}


	/**
	* Copy form PrestaShop core + Limit number of result to avoid to many results
	*/
	private function _searchProductByName($query, $forbiddenIds = array())
	{
		$cookie = Context::getContext()->cookie;

		$result = Db::getInstance()->executeS('
		SELECT p.`id_product`, pl.`name`, p.`active`, p.`reference`, m.`name` AS manufacturer_name, i.`id_image` as id_image, pl.`link_rewrite` as  `link_rewrite`
		FROM `'._DB_PREFIX_.'category_product` cp
		LEFT JOIN `'._DB_PREFIX_.'product` p
			ON p.`id_product` = cp.`id_product`
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
			ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($cookie->id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'manufacturer` m
			ON m.`id_manufacturer` = p.`id_manufacturer`
		LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
			ON pa.`id_product` = p.`id_product`
		LEFT JOIN `'._DB_PREFIX_.'image` i
			ON i.`id_product` = p.`id_product`
			AND cover = 1
		WHERE
			(pl.`name` LIKE \'%'.pSQL($query).'%\'
			OR p.`reference` LIKE \'%'.pSQL($query).'%\'
			OR p.`supplier_reference` LIKE \'%'.pSQL($query).'%\'
			OR pa.`reference` LIKE \'%'.pSQL($query).'%\')
			AND p.`id_product` NOT IN (' . implode(',', $forbiddenIds) . ')
		GROUP BY `id_product`
		ORDER BY pl.`name` ASC
		LIMIT 0,5');

		if (!$result)
			return false;

		$resultsArray = array();
		foreach ($result AS $row)
		{
			$row['price'] = Product::getPriceStatic($row['id_product'], true, NULL, 2);
			$row['quantity'] = Product::getQuantity($row['id_product']);
			$resultsArray[] = $row;
		}
		return $resultsArray;
	}
}
