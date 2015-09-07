<?php
/**
 * Module videodesk - Shop Configuration Criteria model
 *
 * @category   	Module / front_office_features
 * @author     	BM Services
 * @copyright  	2013 BM Services
 * @version   	2.0
 */
class VideodeskShopConfigurationCriteria extends ObjectModel
{
	public $id;
	public $name;
	public $with_value;
	
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'videodesk_shop_configuration_criteria', 
		'primary' => 'id_criteria', 
		'multilang' => false, 
		'fields' => array(
			'name' => array('type' => self::TYPE_STRING),
			'with_value' => array('type' => self::TYPE_BOOL)
		)
	);
	
	/**
	 * Retrieve the whole datas for a criteria by its name
	 * 
	 * @param string $name
	 * @return Resultset|boolean
	 */
	public static function getByName($name)
	{
		if (!empty($name)) {
			$sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' WHERE `name` = "' . Db::getInstance()->escape($name) . '";';
			return Db::getInstance()->getRow($sql);
		}
		return false;
	}
}