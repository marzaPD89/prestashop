<?php
/**
 * Module videodesk - Admin Controller for VideodeskCall model
 *
 * @category   	Module / front_office_features
 * @author     	BM Services
 * @copyright  	2013 BM Services
 * @version   	2.0
 */
class AdminVideodeskCallController extends ModuleAdminController
{
	/**
	 * @see ModuleAdminController::__construct()
	 */
	public function __construct()
	{
		$this->table = 'videodesk_call';
		$this->className = 'VideodeskCall';
		
		$this->lang = false;
		$this->addRowAction('view');
		
		$this->fields_list = array(
			'call_date' => array(
				'title' => $this->l('Call_date'),
				'align' => 'center',
				'width' => 80
			),
			'call_type' => array(
				'title' => $this->l('Call_type'),
				'align' => 'center',
				'width' => 80
			),
			'id_employee' => array(
				'title' => $this->l('Id_employee'),
				'align' => 'center',
				'width' => 80
			),
			'id_customer' => array(
				'title' => $this->l('Id_customer'),
				'align' => 'center',
				'width' => 80
			),
			'id_cart' => array(
				'title' => $this->l('Id_cart'),
				'align' => 'center',
				'width' => 80
			),
			'connexion_page' => array(
				'title' => $this->l('Connexion_page'),
				'align' => 'center',
				'width' => 80
			),
			'call_transcript' => array(
				'title' => $this->l('Call_transcript'),
				'align' => 'center',
				'width' => 80
			)
		);
		
		parent::__construct();
		
		$this->shopLinkType = 'shop';
	}
	
	/**
	 * @see ModuleAdminController::renderList()
	 */
	public function renderList()
	{
		$this->fields_list['id_employee']['callback'] = 'getEmployeeName';
		$this->fields_list['id_customer']['callback'] = 'getCustomerName';
		$this->fields_list['id_cart']['callback'] = 'getCart';
		$this->fields_list['call_date']['callback'] = 'getCallDate';
		$this->fields_list['call_transcript']['callback'] = 'getCallTranscript';
		
		return parent::renderList();
	}
	
	/**
	 * @see AdminController::getList()
	 */
	public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
	{
		$order_by = 'call_date';
		$order_way = 'DESC';
		
		parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);
	}
	
	/**
	 * @see ModuleAdminController::renderView()
	 */
	public function renderView()
	{
		if (!($call = $this->loadObject()))
			return;
	
		$employee_name = $this->getEmployeeName(null, array(
			'id_employee' => $call->id_employee
		));
		$customer_name = $this->getCustomerName(null, array(
			'id_customer' => $call->id_customer
		));
	
// 		if ($call->call_type == "chat" || $call->call_type == "audio") {
			$transcript = $call->ajaxGetTranscription();
// 		}
	
		$this->tpl_view_vars = array(
			'call' => $call,
			'employee_name' => $employee_name,
			'customer_name' => $customer_name,
			'transcript' => $transcript
		);
	
		return parent::renderView();
	}
	
	/**
	 * @see ModuleAdminController::renderForm()
	 */
	public function renderForm()
	{
		$this->fields_form = array(
			'tinymce' => true,
			'legend' => array(
				'title' => $this->l('VideodeskCall'),
				'image' => '../img/t/AdminParentPreferences.gif'
			),
			'input' => array(
				array(
					'type' => 'text',
					'lang' => false,
					'label' => $this->l('Id_customer:'),
					'name' => 'id_customer'
				),
				array(
					'type' => 'text',
					'lang' => false,
					'label' => $this->l('Id_cart:'),
					'name' => 'id_cart'
				),
				array(
					'type' => 'text',
					'lang' => false,
					'label' => $this->l('Id_employee:'),
					'name' => 'id_employee'
				),
				array(
					'type' => 'date',
					'lang' => false,
					'required' => true,
					'label' => $this->l('Call_date:'),
					'name' => 'call_date'
				),
				array(
					'type' => 'text',
					'lang' => false,
					'required' => true,
					'label' => $this->l('Call_type:'),
					'name' => 'call_type'
				),
				array(
					'type' => 'text',
					'lang' => false,
					'required' => true,
					'label' => $this->l('Connexion_page:'),
					'name' => 'connexion_page'
				),
				array(
					'type' => 'textarea',
					'lang' => false,
					'autoload_rte' => true,
					'rows' => 5,
					'cols' => 40,
					'label' => $this->l('Call_transcript:'),
					'name' => 'call_transcript'
				)
			),
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'button'
			)
		);
		
		if (!($obj = $this->loadObject(true)))
			return;
		
		return parent::renderForm();
	}
	
	/**
	 * Retrieve employee full name
	 * @return string
	 */
	public function getEmployeeName($echo, $row)
	{
		if ($row['id_employee'] != 0) {
			$employee = new Employee($row['id_employee']);
			return $employee->firstname . ' ' . $employee->lastname;
		}
		else
			return '--';
	}
	
	/**
	 * Retrieve customer full name
	 * @return string
	 */
	public function getCustomerName($echo, $row)
	{
		if (Customer::customerIdExistsStatic($row['id_customer'])) {
			$customer = new Customer($row['id_customer']);
			return $customer->firstname . ' ' . $customer->lastname;
		}
		else
			return '--';
	}
	
	/**
	 * Smart display if a cart is associated to the call
	 * @return string
	 */
	public function getCart($echo, $row)
	{
		if ($row['id_cart'] == 0) {
			return '--';
		}
		else
			return '<img src="../img/admin/enabled.gif">';
	}
	
	/**
	 * Format the date of the call
	 * @return string
	 */
	public function getCallDate($echo, $row)
	{
		if ($row['call_date'] == '0000-00-00 00:00:00')
			return "--";
		else {
			$date = strtotime($row['call_date']);
			if ($date !== false) {
				return date($this->context->language->date_format_full, $date);
			}
			else {
				return $row['call_date'];
			}
		}
	}
	
	/**
	 * Format the transcript of a call
	 * @return string
	 */
	public function getCallTranscript($echo, $row)
	{
		$transcript_list = explode("|", $row['call_transcript']);
		if (count($transcript_list) > 1) {
			$transcript = "";
			for ($i = 0; $i < 3; $i++) {
				$transcript .= $transcript_list[$i] . "<br />";
			}
			return $transcript;
		} 
		else
			return $row['call_transcript'];
	}
}