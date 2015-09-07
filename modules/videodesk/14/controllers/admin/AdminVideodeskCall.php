<?php
/**
 * Module videodesk - Admin Controller for VideodeskCall model
 *
 * @category   	Module / front_office_features
 * @author     	BM Services
 * @copyright  	2013 BM Services
 * @version   	2.0
 */

require_once(_PS_MODULE_DIR_ . 'videodesk/14/models/VideodeskCall.class.php');

class AdminVideodeskCall extends AdminTab
{
	public function __construct()
	{
		global $cookie, $_LANGADM, $_MODULE;
		
		$this->table = 'videodesk_call';
		$this->className = 'VideodeskCall';
		$this->lang = false;
		$this->edit = false;
		$this->delete = false;
		$this->view = true;
		
		$this->_select = '
    		a.call_date, a.call_type, a.connexion_page, a.call_transcript,
    		CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) AS `customer`,
    		CONCAT(LEFT(e.`firstname`, 1), \'. \', e.`lastname`) AS `employee`,
    		IF((a.id_cart > 1), 1, 0) as cart
    		';
		$this->_join = '
    	LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON (c.`id_customer` = a.`id_customer`)
	 	LEFT JOIN `' . _DB_PREFIX_ . 'employee` e ON (e.`id_employee` = a.`id_employee`)';
		
		$langFile = _PS_MODULE_DIR_.'/videodesk/translations/'.Language::getIsoById(intval($cookie->id_lang)).'.php';
		if(file_exists($langFile))
		{
			require_once $langFile;
			foreach($_MODULE as $key=>$value)
				if(substr(strip_tags($key), 0, 5) == 'Admin')
				$_LANGADM[str_replace('_', '', strip_tags($key))] = $value;
		}
		
		$this->fieldsDisplay = array(
			'call_date' => array(
				'title' => $this->l('Call_date'),
				'type' => 'datetime',
				'align' => 'center',
				'width' => 80,
				'search' => false,
				'orderby' => false
			),
			'call_type' => array(
				'title' => $this->l('Call_type'),
				'align' => 'center',
				'width' => 80,
				'search' => false,
				'orderby' => false
			),
			'employee' => array(
				'title' => $this->l('Employee'),
				'align' => 'center',
				'width' => 80,
				'search' => false,
				'orderby' => false
			),
			'customer' => array(
				'title' => $this->l('Customer'),
				'align' => 'center',
				'width' => 80,
				'search' => false,
				'orderby' => false
			),
			'cart' => array(
				'title' => $this->l('Cart'),
				'align' => 'center',
				'width' => 80,
				'icon' => array(1 => 'enabled.gif', 'default' => 'disabled.gif'),
				'search' => false,
				'orderby' => false
			),
			'connexion_page' => array(
				'title' => $this->l('Connexion_page'),
				'align' => 'center',
				'width' => 80,
				'search' => false,
				'orderby' => false
			),
			'call_transcript' => array(
				'title' => $this->l('Call_transcript'),
				'align' => 'center',
				'width' => 80,
				'search' => false,
				'orderby' => false
			)
		);
		
		$this->identifier = 'id_videodesk_call';
		
		parent::__construct();
	}
	
	/**
	 * Get the current objects' list form the database
	 *
	 * @param integer $id_lang Language used for display
	 * @param string $orderBy ORDER BY clause
	 * @param string $_orderWay Order way (ASC, DESC)
	 * @param integer $start Offset in LIMIT clause
	 * @param integer $limit Row count in LIMIT clause
	 */
	public function getList($id_lang, $orderBy = NULL, $orderWay = NULL, $start = 0, $limit = NULL)
	{
		$order_by = 'call_date';
		$order_way = 'DESC';
		
		parent::getList($id_lang, $order_by, $order_way, $start, $limit);
	}

	/**
	 * View of a call
	 */
	public function viewvideodesk_call()
	{
		global $currentIndex, $cookie, $link;
		$id_call = Tools::getValue('id_videodesk_call');
		
		$call = new VideodeskCall($id_call);
		
		// Customer name
		if ($call->id_customer == 0) {
			$customer_name = '--';
		}
		else {
			$customer = new Customer($call->id_customer);
			$customer_name = $customer->firstname.' '.$customer->lastname;
		}
		
		// Employee name
		if ($call->id_employee == 0) {
			$employee_name = '--';
		}
		else {
			$employee = new Employee($call->id_employee);
			$employee_name = $employee->firstname.' '.$employee->lastname;
		}
		
		// Cart info
		if (isset($call->id_cart) && $call->id_cart != 0) {
			$token = Tools::getAdminToken('AdminCarts'.(int)Tab::getIdFromClassName('AdminCarts').(int)$cookie->id_employee);
			$cart_info = '<a href="index.php?tab=AdminCarts&id_cart='.$call->id_cart.'&viewcart&token='.$token.'"><img src="../img/admin/details.gif" /></a>';
		}
		else {
			$cart_info = '--';
		}
		
		echo '
			<fieldset>
				<strong>'.$this->l('Call date:').'</strong> '.Tools::displayDate($call->call_date, (int)($cookie->id_lang), true).'<br />
				<strong>'.$this->l('Employee:').'</strong> '.$employee_name.'<br />
				<strong>'.$this->l('Customer:').'</strong> '.$customer_name.'<br />
				<strong>'.$this->l('Cart:').'</strong> '.$cart_info.'<br />
				<strong>'.$this->l('Connection page:').'</strong> <a href="'.$call->connexion_page.'" target="_blank"><img src="../img/admin/details.gif" alt="Voir"></a><br />
			</fieldset>
		';
		echo '<br /><br />';
		echo '
			<fieldset>
				<strong>'.$this->l('Transcript:').'</strong><br />'.$this->getCallTranscript($call->call_transcript).'
			</fieldset>
		';		
	}
	
	/**
	 * Format the transcript of a call
	 * @return string
	 */
	public function getCallTranscript($transcript)
	{
		$transcript_list = explode("|", $transcript);
		if (count($transcript_list) > 1) {
			$transcript = "";
			for ($i = 0; $i < 3; $i++) {
				$transcript .= $transcript_list[$i] . "<br />";
			}
			return $transcript;
		}
		else
			return $transcript;
	}
}
