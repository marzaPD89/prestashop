<?php
/**
 * Module videodesk - Shop Configuration Group Pages model
 *
 * @category   	Module / front_office_features
 * @author     	BM Services
 * @copyright  	2013 BM Services
 * @version   	2.0
 */
class VideodeskShopConfigurationGroupPages extends ObjectModel
{
	public $id;
	public $name;
	
	protected $table = 'videodesk_shop_configuration_group_pages';
	protected $identifier = 'id_group_pages';

	public function getFields()
	{
		parent::validateFields();
		$fields['id_group_pages'] = (int)($this->id);
		$fields['name'] = pSQL($this->name);
		return ($fields);
	}
	
	/**
	 * Retrieve group datas from name
	 * 
	 * @param string $name
	 * @return Resultset|boolean
	 */
	public static function getByName($name)
	{
		if (!empty($name)) {
			$sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'videodesk_shop_configuration_group_pages` WHERE `name` = "' . pSQL($name) . '"';
			return Db::getInstance()->getRow($sql);
		}
		return false;
	}
	
	/**
	 * Retrieve all groups
	 * 
	 * @return Resultset|boolean
	 */
	public static function getAllGroups()
	{
		$sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'videodesk_shop_configuration_group_pages`';
		return Db::getInstance()->executeS($sql);
	}
}