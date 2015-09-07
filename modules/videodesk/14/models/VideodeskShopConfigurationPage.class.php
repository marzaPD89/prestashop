<?php
/**
 * Module videodesk - Shop Configuration Page model
 *
 * @category   	Module / front_office_features
 * @author     	BM Services
 * @copyright  	2013 BM Services
 * @version   	2.0
 */
class VideodeskShopConfigurationPage extends ObjectModel
{
	public $id;
	public $id_group_pages;
	public $name;

	protected $table = 'videodesk_shop_configuration_page';
	protected $identifier = 'id_page';
	
	public function getFields()
	{
		parent::validateFields();
		$fields['id_page'] = (int)($this->id);
		$fields['id_group_pages'] = (int)($this->id_group_pages);
		$fields['name'] = pSQL($this->name);
		return ($fields);
	}
	
	/**
	 * Retrieve all pages of a group by name
	 * 
	 * @param string $name
	 * @param int $id_group_pages
	 * @return Resultset|boolean
	 */
	public static function getByName($name, $id_group_pages = null)
	{
		if (!empty($name)) {
			$group = '';
			if (!empty($id_group_pages)) {
				$group = '`id_group_pages` = ' . $id_group_pages . ' AND ';
			}
			$sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'videodesk_shop_configuration_page` WHERE ' . $group . '`name` = "' . pSQL($name) . '"';
			return Db::getInstance()->getRow($sql);
		}
		return false;
	}
	
	/**
	 * Retrieve all pages of a group
	 *
	 * @param int $id_group_pages
	 * @return Resultset|boolean
	 */
	public static function getPagesByGroup($id_group_pages)
	{
		if (!empty($id_group_pages)) {
			$sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'videodesk_shop_configuration_page` WHERE `id_group_pages` = ' . $id_group_pages;
			return Db::getInstance()->executeS($sql);
		}
		return false;
	}
	
	/**
	 * Retrieve page id from group id and page name
	 *
	 * @param int $id_group_pages
	 * @param string $name
	 * @return Resultset|boolean
	 */
	public static function getPageId($id_group_pages, $name)
	{
		if (!empty($id_group_pages) && !empty($name)) {
			$sql = 'SELECT id_page FROM `' . _DB_PREFIX_ . 'videodesk_shop_configuration_page` WHERE `id_group_pages` = ' . $id_group_pages . ' AND `name` = "' . pSQL($name) . '"';
			return Db::getInstance()->getValue($sql);
		}
		return false;
	}
}