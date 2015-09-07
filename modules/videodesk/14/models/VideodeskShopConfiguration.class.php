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
	public $id_conf;
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
	
	protected $fieldsSize = array('id_conf' => 10, 'website_id' => 96, 'displayed' => 1, 'progress_criterias' => 1, 'progress_colors' => 1, 'progress_texts' => 1, 'progress_messages' => 1, 'progress_agent' => 1, 'progressbar_criterias' => 1);
	
	protected $fieldsRequired = array('website_id');
	protected $fieldsValidate = array(
		'website_id' => 'isAnything',
		'displayed' => 'isUnsignedInt',
		'progress_criterias' => 'isUnsignedInt',
		'progress_colors' => 'isUnsignedInt',
		'progress_texts' => 'isUnsignedInt',
		'progress_messages' => 'isUnsignedInt',
		'progress_agent' => 'isUnsignedInt',
		'progressbar_criterias' => 'isUnsignedInt',
		'display_for_all' => 'isUnsignedInt',
		'display_ips' => 'isString',
		'criterias' => 'isUnsignedInt',
		'criterias_all_conditions' => 'isUnsignedInt',
		'scope' => 'isUnsignedInt',
		'ftp_active' => 'isUnsignedInt',
		'ftp_host' => 'isString',
		'ftp_login' => 'isString',
		'ftp_password' => 'isString',
		'ftp_dir' => 'isString',
		'track_stats' => 'isUnsignedInt',
	);
	protected $table = 'videodesk_shop_configuration';
	protected $identifier = 'id_conf';
	
	public function getFields()
	{
		parent::validateFields();
		$fields['id_conf'] = (int) ($this->id);
		$fields['website_id'] = pSQL($this->website_id);
		$fields['displayed'] = pSQL($this->displayed);
		$fields['progress_criterias'] = pSQL($this->progress_criterias);
		$fields['progress_colors'] = pSQL($this->progress_colors);
		$fields['progress_texts'] = pSQL($this->progress_texts);
		$fields['progress_messages'] = pSQL($this->progress_messages);
		$fields['progress_agent'] = pSQL($this->progress_agent);
		$fields['progressbar_criterias'] = pSQL($this->progressbar_criterias);
		$fields['display_for_all'] = pSQL($this->display_for_all);
		$fields['display_ips'] = pSQL($this->display_ips);
		$fields['criterias'] = pSQL($this->criterias);
		$fields['criterias_all_conditions'] = pSQL($this->criterias_all_conditions);
		$fields['scope'] = pSQL($this->scope);
		$fields['ftp_active'] = pSQL($this->ftp_active);
		$fields['ftp_host'] = pSQL($this->ftp_host);
		$fields['ftp_login'] = pSQL($this->ftp_login);
		$fields['ftp_password'] = pSQL($this->ftp_password);
		$fields['ftp_dir'] = pSQL($this->ftp_dir);
		$fields['track_stats'] = pSQL($this->track_stats);
		return ($fields);
	}
	
	/**
	 * Retrieve all shops with videodesk display activated
	 *
	 * @return VideodeskShopConfiguration collection
	 */
	public static function getAllActiveShops()
	{
		$sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'videodesk_shop_configuration WHERE `displayed` = 1';
		$result = Db::getInstance()->executeS($sql);
		
		$shops = array();
		foreach ($result as $shop) {
			$vd_shop = new VideodeskShopConfiguration();
			$vd_shop->id_conf = $shop['id_conf'];
			$vd_shop->website_id = $shop['website_id'];
			$vd_shop->displayed = $shop['displayed'];
			$vd_shop->track_stats = $shop['track_stats'];
			$vd_shop->ftp_active = $shop['ftp_active'];
			$vd_shop->ftp_host = $shop['ftp_host'];
			$vd_shop->ftp_login = $shop['ftp_login'];
			$vd_shop->ftp_password = $shop['ftp_password'];
			$vd_shop->ftp_dir = $shop['ftp_dir'];
			
			$shops[] = $vd_shop;
		}
	
		return $shops;
	}

	/**
	 * Retrieve all shops with videodesk display and FTP activated
	 *
	 * @return VideodeskShopConfiguration collection
	 */
	public static function getAllActiveHistoricShops()
	{
		$sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'videodesk_shop_configuration WHERE `displayed` = 1 AND `ftp_active` = 1';
		$result = Db::getInstance()->executeS($sql);
	
		$shops = array();
		foreach ($result as $shop) {
			$vd_shop = new VideodeskShopConfiguration();
			$vd_shop->id_conf = $shop['id_conf'];
			$vd_shop->website_id = $shop['website_id'];
			$vd_shop->displayed = $shop['displayed'];
			$vd_shop->ftp_active = $shop['ftp_active'];
			$vd_shop->ftp_host = $shop['ftp_host'];
			$vd_shop->ftp_login = $shop['ftp_login'];
			$vd_shop->ftp_password = $shop['ftp_password'];
			$vd_shop->ftp_dir = $shop['ftp_dir'];
			
			$shops[] = $vd_shop;
		}
	
		return $shops;
	}
	
	/**
	 * Retrieve shop configuration for a Website ID
	 * 
	 * @param string $website_id
	 * @return VideodeskShopConfiguration|boolean
	 */
	public static function getBywebsite_id($website_id)
	{
		$sql = 'SELECT `id_conf` FROM `' . _DB_PREFIX_ . 'videodesk_shop_configuration` 
                WHERE `website_id` LIKE "' . $website_id . '"';
		
		$result = Db::getInstance()->executes($sql);
		$obj = new VideodeskShopConfiguration($result);
		
		if (!is_null($obj))
			return $obj;
		else
			return false;
	}
	
	/**
	 * Retrieve the shop configuration
	 * 
	 * @return configuration row
	 */
	public static function getConf()
	{
		$sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'videodesk_shop_configuration`';
		$result = Db::getInstance()->executes($sql);
		return $result[0];
		
	}
	
	/**
	 * Retrieve the Website Id of a shop
	 *
	 * @return VideodeskShopConfiguration collection
	 */
	public static function getWebsite_idByIdShops($id_shop)
	{
		$sql = 'SELECT `website_id` FROM `' . _DB_PREFIX_ . 'videodesk_shop_configuration` WHERE `id_conf` = ' . $id_shop;
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
		$sql = 'SELECT `id_conf`, `website_id` FROM ' . _DB_PREFIX_ . 'videodesk_shop_configuration`';
		$result = Db::getInstance()->executeS($sql);
	
		$websites = array();
		foreach ($result as $website) {
			$websites[$website['id_conf']] = $website['website_id'];
		}
	
		return $websites;
	}
}
