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

	protected $table = 'videodesk_shop_configuration_criteria_value';
	protected $identifier = 'id_criteria';
	
	public function getFields()
	{
		parent::validateFields();
		$fields['id_criteria'] = (int)($this->id);
		$fields['active'] = (int)($this->active);
		$fields['value'] = pSQL($this->value);
		return ($fields);
	}
	
	/**
	 * @see ObjectModel::__construct()
	 */
	public function __construct($id = null, $id_lang = null)
	{
		if (!empty($id) && !empty($id_shop)) {
			$sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'videodesk_shop_configuration_criteria_value` WHERE `id_criteria` = ' . $id . ' AND `id_shop` = 1';
			$result = Db::getInstance()->getRow($sql);
			if (!empty($result)) {
				$this->id_criteria = $result['id_criteria'];
				$this->active = $result['active'];
				$this->value = $result['value'];
			}
			return $this;
		}
		parent::__construct();
	}
	
	/**
	 * @see ObjectModel::add()
	 */
	public function add($autodate = true, $nullValues = false)
	{
		if (!empty($this->id_criteria) && !empty($this->id_shop)) {
			return Db::getInstance()->autoExecuteWithNullValues(
				_DB_PREFIX_.'videodesk_shop_configuration_criteria_value',
				array(
					'id_shop' => $this->id_shop,
					'id_criteria' => $this->id_criteria,
					'active' => $this->active,
					'value' => $this->value
				),
				'INSERT');
		}
		return false;
	}
	
	/**
	 * @see ObjectModel::update()
	 */
	public function update($null_values = false)
	{
		if (!empty($this->id_criteria) && !empty($this->id_shop)) {
			return Db::getInstance()->autoExecuteWithNullValues(
				_DB_PREFIX_.'videodesk_shop_configuration_criteria_value',
				array('active' => $this->active, 'value' => $this->value),
				'UPDATE',
				'id_criteria = '. $this->id_criteria . ' AND id_shop = 1');
		}
		return false;
	}
	
	/**
	 * Disable a shop 
	 *  
	 * @param int $id_shop
	 * @return boolean
	 */
	public static function DeleteAllForShop($id_shop)
	{
		return Db::getInstance()->delete(_DB_PREFIX_.'videodesk_shop_configuration_criteria_value');
	}
}