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
	
	protected $table = 'videodesk_shop_configuration_criteria';
	protected $identifier = 'id_criteria';
	
	public function getFields()
	{
		parent::validateFields();
		$fields['id_criteria'] = (int)($this->id);
		$fields['name'] = pSQL($this->name);
		$fields['with_value'] = (int)($this->with_value);
		return ($fields);
	}
	
	/**
	 * Retrieve the whole datas for a criteria by its name
	 * 
	 * @param string $name
	 * @return Resultset|boolean
	 */
	public static function getByName($name)
	{
		if (!empty($name)) {
			$sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'videodesk_shop_configuration_criteria WHERE `name` = "' . pSQL($name) . '"';
			return Db::getInstance()->getRow($sql);
		}
		return false;
	}
}