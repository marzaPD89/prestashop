<?php
/**
 * Module videodesk - Historic Log model
 *
 * @category   	Module / front_office_features
 * @author     	BM Services
 * @copyright  	2013 BM Services
 * @version   	2.0
 */
class VideodeskHistoricLog extends ObjectModel
{
	public $id;
	public $id_shop;
	public $filename;
	public $size;
	public $date_add;
	public $date_upd;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
			'table' => 'videodesk_historic_log',
			'primary' => 'id',
			'multilang' => false,
			'fields' => array(
				'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true, ),
				'filename' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, ),
				'size' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true, ),
				'date_add' => array('type' => self::TYPE_DATE),
				'date_upd' => array('type' => self::TYPE_DATE),
			)
	);
	
	/**
	 * Save current object to database (add or update)
	 *
	 * @param bool $null_values
	 * @param bool $autodate
	 * @return boolean Insertion result
	 */
	public function save($null_values = false, $autodate = true)
	{
		$sql = 'SELECT `id_videodesk_historic_log` FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' WHERE `id_shop` = ' . (int) $this->id_shop . ' AND `filename` = "' . $this->filename . '";';
		$row = Db::getInstance()->getRow($sql);
		
		if (!empty($row)) {
			$this->id = $row['id_videodesk_historic_log'];
			return $this->update($null_values);
		}
		else {
			return $this->add($autodate, $null_values);
		}
	}
	
	/**
	 * Retrieve the files (and size) already imported
	 * 
	 * @param int $id_shop
	 * @return Resultset
	 */
	public static function getFilesForShop($id_shop)
	{
		$sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' WHERE `id_shop` = ' . $id_shop . ' ORDER BY `date_add`;';
		return Db::getInstance()->executeS($sql);
	}
}