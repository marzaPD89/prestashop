<?php
/**
 * Module videodesk - Shop Configuration Page Value model
 *
 * @category   	Module / front_office_features
 * @author     	BM Services
 * @copyright  	2013 BM Services
 * @version   	2.0
 */
class VideodeskShopConfigurationPageValue extends ObjectModel
{
	public $id;
	public $id_page;
	public $id_shop;
	
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'videodesk_shop_configuration_page_value',
		'primary' => 'id_page',
		'multilang' => false,
		'multishop' => true,
		'fields' => array(
			'id_page' => array('type' => self::TYPE_INT),
			'id_shop' => array('type' => self::TYPE_INT)
		)
	);
	
	/**
	 * @see ObjectModel::__construct()
	 */
	public function __construct($id = null, $id_lang = null, $id_shop = null)
	{
		if (!empty($id) && !empty($id_shop)) {
			$sql = 'SELECT * FROM `' . _DB_PREFIX_ . self::$definition['table'] . '` WHERE `' . self::$definition['primary'] . '` = ' . $id . ' AND `id_shop` = ' . $id_shop;
			$result = Db::getInstance()->getRow($sql);
			if (!empty($result)) {
				ObjectModel::hydrate($result);
			}
			return $result;
		}
		parent::__construct($id, $id_lang, $id_shop);
	}
	
	/**
	 * @see ObjectModel::add()
	 */
	public function add($autodate = true, $null_values = false)
	{
		if (!empty($this->id_page) && !empty($this->id_shop)) {
			return Db::getInstance()->insert(self::$definition['table'], array(
				'id_shop' => $this->id_shop,
				'id_page' => $this->id_page
			));
		}
		return false;
	}
	
	/**
	 * @see ObjectModel::delete()
	 */
	public function delete()
	{
		if (!empty($this->id_page) && !empty($this->id_shop)) {
			return Db::getInstance()->delete(self::$definition['table'], '`id_page` = ' . $this->id_page . ' AND `id_shop` = ' . $this->id_shop);
		}
		return false;
	}
	
	/**
	 * Retrieve the active pages
	 * 
	 * @param string $group_name
	 * @param int $id_shop
	 * @param string $name
	 * @return VideodeskShopConfigurationPage as array|boolean
	 */
	public static function getActiveValue($group_name, $id_shop, $name)
	{
		$group_pages = VideodeskShopConfigurationGroupPages::getByName($group_name);
		if (!empty($id_shop) && !empty($name) && !empty($group_pages['id_group_pages'])) {
			$page = VideodeskShopConfigurationPage::getByName($name, $group_pages['id_group_pages']);
			if (!empty($page)) {
				$sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' WHERE `id_page` = ' . $page['id_page'] . ' AND `id_shop` = ' . $id_shop;
				$result = Db::getInstance()->getRow($sql);
				$page['active'] = 0;
				if (!empty($result)) {
					$page['active'] = 1;
				}
				return $page;
			}
		}
		return false;
	}
	
	/**
	 * Delete configuration for all shops
	 * 
	 * @param int $id_shop
	 * @return boolean
	 */
	public static function deleteAllForShop($id_shop)
	{
		if (!empty($id_shop)) {
			return Db::getInstance()->delete(self::$definition['table'], '`id_shop` = ' . $id_shop);
		}
	}
}