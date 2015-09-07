<?php
/**
 * Module videodesk - Shop Configuration Criteria Value model
 *
 * @category   	Module / front_office_features
 * @author     	BM Services
 * @copyright  	2013 BM Services
 * @version   	2.0
 */
class VideodeskShopConfigurationCriteriaValue extends ObjectModel
{
	public $id;
	public $id_criteria;
	public $id_shop;
	public $active;
	public $value;
	
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'videodesk_shop_configuration_criteria_value',
		'primary' => 'id_criteria',
		'multilang' => false,
		'multishop' => true,
		'fields' => array(
			'active' => array('type' => self::TYPE_BOOL),
			'value' => array('type' => self::TYPE_STRING)
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
				$result['id'] = $id;
				ObjectModel::hydrate($result);
			}
			return $result;
		}
		parent::__construct();
	}
	
	/**
	 * @see ObjectModel::add()
	 */
	public function add($autodate = true, $null_values = false)
	{
		if (!empty($this->id_criteria) && !empty($this->id_shop)) {
			return Db::getInstance()->insert(self::$definition['table'], array(
				'id_shop' => $this->id_shop,
				'id_criteria' => $this->id_criteria,
				'active' => $this->active,
				'value' => $this->value
			));
		}
		return false;
	}
	
	/**
	 * @see ObjectModel::update()
	 */
	public function update($null_values = false)
	{
		if (!empty($this->id_criteria) && !empty($this->id_shop)) {
			return Db::getInstance()->update(self::$definition['table'], array(
				'active' => $this->active,
				'value' => $this->value
			), self::$definition['primary'] . ' = ' . $this->id_criteria . ' AND `id_shop` = ' . $this->id_shop);
		}
		return false;
	}
	
	/**
	 * Disable a shop 
	 *  
	 * @param int $id_shop
	 * @return boolean
	 */
	public static function EnableAllForShop($id_shop)
	{
		return Db::getInstance()->update(self::$definition['table'], array(
			'active' => 0
		), 'id_shop = ' . $id_shop);
	}
}