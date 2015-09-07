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

	protected $table = 'videodesk_historic_log';
	protected $identifier = 'id_videodesk_historic_log';
	
	public function getFields()
	{
		parent::validateFields();
		$fields['id_videodesk_historic_log'] = (int) ($this->id);
		$fields['id_shop'] = (int) ($this->id_shop);
		$fields['filename'] = pSQL($this->filename);
		$fields['size'] = (int) ($this->size);
		$fields['date_add'] = pSQL($this->date_add);
		$fields['date_upd'] = pSQL($this->date_upd);
		return ($fields);
	}
	
	/**
	 * Save current object to database (add or update)
	 *
	 * @param bool $null_values
	 * @param bool $autodate
	 * @return boolean Insertion result
	 */
	public function save($null_values = false, $autodate = true)
	{
		$sql = 'SELECT `id_videodesk_historic_log` FROM ' . _DB_PREFIX_ . 'videodesk_historic_log WHERE `id_shop` = 1 AND `filename` = "' . $this->filename . '"';
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
		$sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'videodesk_historic_log WHERE `id_shop` = 1 ORDER BY `date_add`';
		return Db::getInstance()->executeS($sql);
	}
}