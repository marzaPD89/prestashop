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
	
	protected $table = 'videodesk_shop_configuration_page_value';
	protected $identifier = 'id_page';
	
	public function getFields()
	{
		parent::validateFields();
		$fields['id_page'] = (int)($this->id);
		$fields['id_shop'] = pSQL($this->id_shop);
		return ($fields);
	}
	
	/**
	 * @see ObjectModel::__construct()
	 */
	public function __construct($id = null, $id_lang = null)
	{
		if (!empty($id) && !empty($id_shop)) {
			$sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'videodesk_shop_configuration_page_value` WHERE `id_page` = ' . $id . ' AND `id_shop` = 1';
			$result = Db::getInstance()->getRow($sql);
			if (!empty($result)) {
				$this->id_page = $result['id_page'];
				$this->id_shop = $result['id_shop'];
			}
			return $this;
		}
		parent::__construct($id, $id_lang);
	}
	
	/**
	 * @see ObjectModel::add()
	 */
	public function add($autodate = true, $nullValues = false)
	{
		if (!empty($this->id_page) && !empty($this->id_shop)) {
			return Db::getInstance()->autoExecuteWithNullValues(
				_DB_PREFIX_.'videodesk_shop_configuration_page_value',
				array('id_shop' => $this->id_shop, 'id_page' => $this->id_page),
				'INSERT');
		}
		return false;
	}
	
	/**
	 * @see ObjectModel::delete()
	 */
	public function delete()
	{
		if (!empty($this->id_page) && !empty($this->id_shop)) {
			return Db::getInstance()->delete(_DB_PREFIX_.'videodesk_shop_configuration_page_value', '`id_page` = ' . $this->id_page . ' AND `id_shop` = ' . $this->id_shop);
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
				$sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'videodesk_shop_configuration_page_value WHERE `id_page` = ' . $page['id_page'] . ' AND `id_shop` = ' . $id_shop;
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
			return Db::getInstance()->delete(_DB_PREFIX_.'videodesk_shop_configuration_page_value', '`id_shop` = ' . $id_shop);
		}
	}
}