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
	
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'videodesk_shop_configuration_group_pages',
		'primary' => 'id_group_pages',
		'multilang' => false,
		'fields' => array(
			'name' => array('type' => self::TYPE_STRING)
		)
	);
	
	/**
	 * Retrieve group datas from name
	 * 
	 * @param string $name
	 * @return Resultset|boolean
	 */
	public static function getByName($name)
	{
		if (!empty($name)) {
			$sql = 'SELECT * FROM `' . _DB_PREFIX_ . self::$definition['table'] . '` WHERE `name` = "' . Db::getInstance()->escape($name) . '";';
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
		$sql = 'SELECT * FROM `' . _DB_PREFIX_ . self::$definition['table'] . '`;';
		return Db::getInstance()->executeS($sql);
	}
}