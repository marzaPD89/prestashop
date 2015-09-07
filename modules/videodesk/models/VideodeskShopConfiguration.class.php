<?php
/**
 * Module videodesk - Shop Configuration model
 *
 * @category   	Module / front_office_features
 * @author     	BM Services
 * @copyright  	2013 BM Services
 * @version   	2.0
 */
class VideodeskShopConfiguration extends ObjectModel
{
	public $id;
	public $id_shop;
	public $website_id;
	public $displayed;
	public $progress_criterias;
	public $progress_colors;
	public $progress_texts;
	public $progress_messages;
	public $progress_agent;
	public $progressbar_criterias;
	public $display_for_all;
	public $display_ips;
	public $criterias;
	public $criterias_all_conditions;
	public $scope;
	public $ftp_active;
	public $ftp_host;
	public $ftp_login;
	public $ftp_password;
	public $ftp_dir;
	public $track_stats;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'videodesk_shop_configuration',
		'primary' => 'id_shop',
		'multilang' => false,
		'fields' => array(
			'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true, ),
			'website_id' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, ),
            'displayed' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', ),
            'progress_criterias' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', ),
            'progress_colors' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', ),
            'progress_texts' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', ),
            'progress_messages' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', ),
            'progress_agent' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', ),
            'progressbar_criterias' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', ),
            'display_for_all' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', ),
            'display_ips' => array('type' => self::TYPE_STRING, 'validate' => 'isString', ),
            'criterias' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', ),
            'criterias_all_conditions' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', ),
			'scope' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', ),
			'ftp_active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', ),
			'ftp_host' => array('type' => self::TYPE_STRING, 'validate' => 'isString', ),
			'ftp_login' => array('type' => self::TYPE_STRING, 'validate' => 'isString', ),
			'ftp_password' => array('type' => self::TYPE_STRING, 'validate' => 'isString', ),
			'ftp_dir' => array('type' => self::TYPE_STRING, 'validate' => 'isString', ),
			'track_stats' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', ),
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
		$sql = 'SELECT `id_shop` FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' WHERE `id_shop` = ' . (int) $this->id_shop . ';';
		$row = Db::getInstance()->getRow($sql);
		
		if (!empty($row)) {
			parent::update();
			return Db::getInstance()->update(
				self::$definition['table'], 
				array('website_id' => $this->website_id),
				'`id_shop` = ' . (int) $this->id_shop);
		}
		else {
			return $this->add($autodate, $null_values);
		}
	}
	
	/**
	 * Retrieve all shops with videodesk display activated
	 * 
	 * @return VideodeskShopConfiguration collection
	 */
	public static function getAllActiveShops()
	{
		$sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' WHERE `displayed` = 1;';
		$result = Db::getInstance()->executeS($sql);
		
		return self::hydrateCollection(__CLASS__, $result);
	}
	
	/**
	 * Retrieve all shops with videodesk display and FTP activated
	 *
	 * @return VideodeskShopConfiguration collection
	 */
	public static function getAllActiveHistoricShops()
	{
		$sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' WHERE `displayed` = 1 AND `ftp_active` = 1;';
		$result = Db::getInstance()->executeS($sql);
		
		return self::hydrateCollection(__CLASS__, $result);
	}
	
	/**
	 * Retrieve the Website Id of a shop
	 *
	 * @return VideodeskShopConfiguration collection
	 */
	public static function getWebsite_idByIdShops($id_shop)
	{
		$sql = 'SELECT `website_id` FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' WHERE `id_shop` = ' . $id_shop . ';';
		$row = Db::getInstance()->getRow($sql);
		
		return $row['website_id'];
	}
	
	/**
	 * Retrieve all the shops  configured with the Website Id
	 * 
	 * @return array[id_shop][website_id]
	 */
	public static function getWebsiteIds()
	{
		$sql = 'SELECT `id_shop`, `website_id` FROM ' . _DB_PREFIX_ . self::$definition['table'] . ';';
		$result = Db::getInstance()->executeS($sql);
		
		$websites = array();
		foreach ($result as $website) {
			$websites[$website['id_shop']] = $website['website_id'];
		}
		
		return $websites;
	}
	
}