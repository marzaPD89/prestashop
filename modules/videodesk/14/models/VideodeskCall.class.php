<?php
/**
 * Module videodesk - Call model
 *
 * @category   	Module / front_office_features
 * @author     	BM Services
 * @copyright  	2013 BM Services
 * @version   	2.0
 */
class VideodeskCall extends ObjectModel
{
	public $id;
	public $id_shop;
	public $id_customer;
	public $id_cart;
	public $id_employee;
	public $call_date;
	public $call_type;
	public $connexion_page;
	public $call_transcript;
	public $date_add;
	public $date_upd;
	
	private static $date_format = array('d/m/Y H:i:s', 'd/m/Y H:i', 'Y-m-d H:i:s', 'Y-m-d H:i');

	protected $table = 'videodesk_call';
	protected $identifier = 'id_videodesk_call';
	
	public function getFields()
	{
		parent::validateFields();
		$fields['id_videodesk_call'] = (int) ($this->id);
		$fields['id_shop'] = (int) ($this->id_shop);
		$fields['id_customer'] = (int) ($this->id_customer);
		$fields['id_cart'] = (int) ($this->id_cart);
		$fields['id_employee'] = (int) ($this->id_employee);
		$fields['call_date'] = pSQL($this->call_date);
		$fields['call_type'] = pSQL($this->call_type);
		$fields['connexion_page'] = pSQL($this->connexion_page);
		$fields['call_transcript'] = pSQL($this->call_transcript);
		$fields['date_add'] = pSQL($this->date_add);
		$fields['date_upd'] = pSQL($this->date_upd);
		return ($fields);
	}
	
	/**
	 * Import the CSV file in database
	 * 
	 * @param $csvs is an array of line
	 */
	public static function importCsv($csvs, $id_shop = null)
	{
// 		if (is_null($id_shop))
// 			$id_shop = Configuration::get('PS_SHOP_DEFAULT');
		$id_shop = 1;
		
		foreach ($csvs as $csv) {
			$call = new VideodeskCall();
			
			if (Validate::isEmail($csv[3]) && Employee::employeeExists($csv[3])) {
				$employee = new Employee();
				$employee = $employee->getByEmail($csv[3]);
				$employee_id = $employee->id;
			}
			else {
				$employee_id = 0;
			}
			
			$call->id_shop = $id_shop;
			$call->id_customer = empty($csv[11]) ? "0" : $csv[11];
			$call->id_cart = empty($csv[12]) ? "0" : $csv[12];
			$call->id_employee = $employee_id;
			$call->call_type = $csv[1];
			
// 			foreach (self::$date_format as $format) {
// 				if (($date = date_create_from_format($format, $csv[0])) !== false) {
// 					break;
// 				}
// 			}
			if (($date = strtotime($csv[0])) === false) {
				$date = time();
			}
				
			$call->call_date = date('Y-m-d H:i:s', $date);
			$call->connexion_page = $csv[4];
			$call->call_transcript = $csv[13];
			$call->id = VideodeskCall::callExist(
				$call->id_customer,
				$call->id_employee,
				$call->id_cart,
				$call->id_shop,
				$call->call_date
			);
			
			$call->save();
		}
		return true;
	}
	
	/**
	 * Check if a call exists for this criterias
	 * 
	 * @return null if not exist
	 */
	public static function callExist($id_customer, $id_employee, $id_cart, $id_shop, $date)
	{
		$sql = 'SELECT `id_videodesk_call` FROM `' . _DB_PREFIX_ . 'videodesk_call` 
                    WHERE `id_customer` = ' . $id_customer . ' 
                    AND `id_cart` = ' . $id_cart . '
                    AND `id_shop` = ' . $id_shop . '
                    AND `id_employee` = ' . $id_employee . '
                    AND `call_date` = "' . $date . '"';
		$result = Db::getInstance()->executes($sql);
		
		if ($result) {
			return $result[0]['id_videodesk_call'];
		}
		else {
			return null;
		}
	}
	
	/**
	 * Retrieve calls associated to an order
	 * 
	 * @param int $id_cart
	 * @param int $id_shop
	 * @return array structure
	 */
	public static function getCallsFromOrder($id_cart, $id_shop)
	{
// 		if (is_null($id_shop))
// 			$id_shop = Configuration::get('PS_SHOP_DEFAULT');
		$id_shop = 1;
		
		$sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'videodesk_call` 
                    WHERE `id_cart` = ' . $id_cart . '';
//                     AND `id_shop` = ' . $id_shop;
		
		$results = Db::getInstance()->executes($sql);
		$data = array();
		foreach ($results as $k => $result) {
			$d = new VideodeskCall;
			$d->id = $result['id_videodesk_call'];
			$d->id_shop = $result['id_shop'];
			$d->id_customer = $result['id_customer'];
			$d->id_cart = $result['id_cart'];
			$d->id_employee = $result['id_employee'];
			$d->call_date = $result['call_date'];
			$d->call_type = $result['call_type'];
			$d->connexion_page = $result['connexion_page'];
			$d->call_transcript = $result['call_transcript'];
			$d->date_add = $result['date_add'];
			$d->date_upd = $result['date_upd'];
			
			$data[$k]['call'] = $d;
			$data[$k]['customer'] = new Customer($d->id_customer);
			$data[$k]['employee'] = new Employee($d->id_employee);
// 			$shop = Shop::getShop($d->id_shop);
// 			$data[$k]['shop_name'] = $shop['name'];
		}
		
		if (!empty($data))
			return $data;
		else
			return false;
	}
	
	/**
	 * Retrieve calls associated to a customer
	 * 
	 * @param int $id_customer
	 * @param int $id_shop
	 * @return array structure
	 */
	public static function getCallsFromCustomer($id_customer, $id_shop)
	{
// 		if (is_null($id_shop))
// 			$id_shop = Configuration::get('PS_SHOP_DEFAULT');
		$id_shop = 1;
		
		$sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'videodesk_call` 
                    WHERE `id_customer` = ' . $id_customer . '';
//                     AND `id_shop` = ' . $id_shop;
		
		$results = Db::getInstance()->executes($sql);
		$data = array();
		foreach ($results as $k => $result) {
			$d = new VideodeskCall;
			$d->id = $result['id_videodesk_call'];
			$d->id_shop = $result['id_shop'];
			$d->id_customer = $result['id_customer'];
			$d->id_cart = $result['id_cart'];
			$d->id_employee = $result['id_employee'];
			$d->call_date = $result['call_date'];
			$d->call_type = $result['call_type'];
			$d->connexion_page = $result['connexion_page'];
			$d->call_transcript = $result['call_transcript'];
			$d->date_add = $result['date_add'];
			$d->date_upd = $result['date_upd'];
			
			$data[$k]['call'] = $d;
			$data[$k]['customer'] = new Customer($d->id_customer);
			$data[$k]['employee'] = new Employee($d->id_employee);
// 			$shop = Shop::getShop($d->id_shop);
// 			$data[$k]['shop_name'] = $shop['name'];
		}
		
		if (!empty($data))
			return $data;
		else
			return false;
	}
	
	/**
	 * Format a chat transcript
	 * 
	 * @return string
	 */
	public function ajaxGetTranscription()
	{
		
		$data = explode("|", $this->call_transcript);
		$html = "";
		foreach ($data as $d) {
			$html .= '<p>' . $d . '</p>';
		}
		return $html;
	}
	
	/**
	 * Retrieve number of calls on a period
	 * 
	 * @param sql datetime interval $date_interval
	 * @return int
	 */
	public static function statsGetNbCalls($date_interval)
	{
		$sql = 'SELECT COUNT(*) as count FROM `' . _DB_PREFIX_ . 'videodesk_call`
					WHERE `call_date` BETWEEN ' . $date_interval;
		return Db::getInstance()->getValue($sql);
	}
	
	/**
	 * Retrieve the number of calls by type on a period
	 * 
	 * @param sql datetime interval $date_interval
	 * @return resultset
	 */
	public static function statsGetNbCallsByType($date_interval)
	{
		$sql = 'SELECT `call_type`, COUNT(*) as count FROM `' . _DB_PREFIX_ . 'videodesk_call`
					WHERE `call_date` BETWEEN ' . $date_interval . ' 
					GROUP BY `call_type`
					ORDER BY `call_type`';
		$result = Db::getInstance()->executeS($sql);
		usort($result, array(__CLASS__, "sortByCount"));
		return $result;
	}
	
	/**
	 * Retrieve the number of calls by employee
	 * 
	 * @param sql datetime interval $date_interval
	 * @return resultset
	 */
	public static function statsGetNbCallsByEmployee($date_interval)
	{
		$sql = 'SELECT `id_employee`, COUNT(*) as count FROM `' . _DB_PREFIX_ . 'videodesk_call`
					WHERE `call_date` BETWEEN ' . $date_interval . '
					GROUP BY `id_employee`';
		$result = Db::getInstance()->executeS($sql);
		usort($result, array(__CLASS__,	"sortByCount"));
		return $result;
	}
	
	/**
	 * Retrieve the number of calls, with cart and order association
	 * 
	 * @param sql datetime interval $date_interval
	 * @return multitype:number
	 */
	public static function statsGetCallsConversion($date_interval)
	{
		$sql = 'SELECT DISTINCT(`id_cart`) FROM `' . _DB_PREFIX_ . 'videodesk_call`
					WHERE `id_cart` != 0 AND `call_date` BETWEEN ' . $date_interval;
		$carts = Db::getInstance()->executeS($sql);
		$nb_carts = count($carts);
		
		$sql = 'SELECT `id_order` FROM `' . _DB_PREFIX_ . 'orders` WHERE `id_cart` IN (SELECT DISTINCT(`id_cart`) FROM `' . _DB_PREFIX_ . 'videodesk_call`
					WHERE `id_cart` != 0 AND  `call_date` BETWEEN ' . $date_interval . ')';
		$orders = Db::getInstance()->executeS($sql);
		$nb_orders = count($orders);
		
		$sql = 'SELECT COUNT(`id_order`) as `count` FROM `' . _DB_PREFIX_ . 'orders` WHERE `date_add` BETWEEN ' . $date_interval;
		$nb_total_orders = Db::getInstance()->getValue($sql);

		$nb_abandoned_carts = $nb_carts - $nb_orders;
		
		$result = array(
			'carts' => $nb_carts,
			'orders' => $nb_orders,
			'total_orders' => $nb_total_orders,
			'abandoned_carts' => $nb_abandoned_carts
		);
		
		return $result;
	}
	
	/**
	 * Calculate the number of visitors who had a Videodesk conversation
	 *
	 * @return int
	 */
	public static function statsGetGuestsCalls($date_interval)
	{
		$sql = 'SELECT `id_customer`, COUNT(*) as `count` FROM `' . _DB_PREFIX_ . 'videodesk_call`
					WHERE `call_date` BETWEEN ' . $date_interval. 
					'GROUP BY `id_customer`';
		$calls = Db::getInstance()->executeS($sql);
		
		$nb_calls = 0;
		foreach ($calls as $call) {
			if ($call['id_customer'] == '0') {
				$nb_calls += (int) $call['count'];
			}
			else {
				$nb_calls++;
			}
		}

		return $nb_calls;
	}
	
	/**
	 * Calculate the number of visitors
	 * 
	 * @return int
	 */
	public static function statsGetTotalGuests($date_interval)
	{
		$sql = 'SELECT COUNT(DISTINCT c.`id_guest`)
				FROM `'._DB_PREFIX_.'connections` c
				WHERE c.`date_add` BETWEEN '.$date_interval;
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
	}
	
	/**
	 * Sort a count array by count
	 */
	public static function sortByCount($a, $b)
	{
		if ($a['count'] == $b['count']) {
			return 0;
		}
		return ($a < $b) ? +1 : -1;
	}
	
}