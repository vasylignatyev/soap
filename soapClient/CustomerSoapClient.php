<?php
/**
 * @service CustomerSoapClient
 */
class CustomerSoapClient{
	/**
	 * The WSDL URI
	 *
	 * @var string
	 */
	public static $_WsdlUri='http://localhost/soap/CustomerSoap.php?WSDL';
	/**
	 * The PHP SoapClient object
	 *
	 * @var object
	 */
	public static $_Server=null;

	/**
	 * Send a SOAP request to the server
	 *
	 * @param string $method The method name
	 * @param array $param The parameters
	 * @return mixed The server response
	 */
	public static function _Call($method,$param){
		if(is_null(self::$_Server))
			self::$_Server=new SoapClient(self::$_WsdlUri);
		return self::$_Server->__soapCall($method,$param);
	}

	/**
	 * Set Session Id
	 *
	 * @param string $session_id
	 * @return boolean
	 */
	public function set_session_id($session_id){
		return self::_Call('set_session_id',Array(
			$session_id
		));
	}

	/**
	 * Get Customer Info List
	 *
	 * @return GetCustomerListResponse
	 */
	public function get_customer_list(){
		return self::_Call('get_customer_list',Array(
		));
	}

	/**
	 * Get Customer Info
	 *
	 * @param GetCustomerInfoRequest $getCustomerInfoRequest
	 * @return GetCustomerInfoResponse
	 */
	public function get_customer_info($getCustomerInfoRequest){
		return self::_Call('get_customer_info',Array(
			$getCustomerInfoRequest
		));
	}

	/**
	 * Add Customer
	 *
	 * @param AddCustomerRequest $addCustomerRequest
	 * @return AddCustomerResponse
	 */
	public function add_customer($addCustomerRequest){
		return self::_Call('add_customer',Array(
			$addCustomerRequest
		));
	}

	/**
	 * Delete Customer
	 *
	 * @param DeleteCustomerRequest $deleteCustomerRequest
	 * @return DeleteCustomerResponse
	 */
	public function delete_customer($deleteCustomerRequest){
		return self::_Call('delete_customer',Array(
			$deleteCustomerRequest
		));
	}
}

/**
 * Class CustomerInfo
 *
 * @pw_element int $i_customer The Customer ID
 * @pw_element string $email The Customer email
 * @pw_element string $password The Customer email
 * @pw_element float $balance A User Balance
 * @pw_element int $billing_model The Customer billing model(0 â debit, 1 - credit)
 * @pw_element boolean $blocked The Customer state: 0 â Customer blocked, 1 â Customer unblocked
 * @pw_element boolean $active The Customer state: 0 - not active, 1 - active
 * @pw_element date $issue_date Date when the customer was created
 * @pw_element date $first_usage Date when the customer was first logged in
 * @pw_element date $last_usage Date when the customer was last logged in
 * @pw_complex CustomerInfo
 */
class CustomerInfo{
	/**
	 * The Customer ID
	 *
	 * @var int
	 */
	public $i_customer;
	/**
	 * The Customer email
	 *
	 * @var string
	 */
	public $email;
	/**
	 * The Customer email
	 *
	 * @var string
	 */
	public $password;
	/**
	 * A User Balance
	 *
	 * @var float
	 */
	public $balance;
	/**
	 * The Customer billing model(0 â debit, 1 - credit)
	 *
	 * @var int
	 */
	public $billing_model;
	/**
	 * The Customer state: 0 â Customer blocked, 1 â Customer unblocked
	 *
	 * @var boolean
	 */
	public $blocked;
	/**
	 * The Customer state: 0 - not active, 1 - active
	 *
	 * @var boolean
	 */
	public $active;
	/**
	 * Date when the customer was created
	 *
	 * @var date
	 */
	public $issue_date;
	/**
	 * Date when the customer was first logged in
	 *
	 * @var date
	 */
	public $first_usage;
	/**
	 * Date when the customer was last logged in
	 *
	 * @var date
	 */
	public $last_usage;
}


/**
 * Class GetCustomerListResponse
 *
 * @pw_element CustomerInfoArray $customer_info_array
 * @pw_complex GetCustomerListResponse
 */
class GetCustomerListResponse{
	/**
	 * @var CustomerInfoArray
	 */
	public $customer_info_array;
}

/**
 * Class GetCustomerInfoRequest
 *
 * @pw_element int $i_customer The Customer ID
 * @pw_complex GetCustomerInfoRequest
 */
class GetCustomerInfoRequest{
	/**
	 * The Customer ID
	 *
	 * @var int
	 */
	public $i_customer;
}

/**
 * Class GetCustomerInfoResponse
 *
 * @pw_element CustomerInfo $customer_info
 * @pw_complex GetCustomerInfoResponse
 */
class GetCustomerInfoResponse{
	/**
	 * @var CustomerInfo
	 */
	public $customer_info;
}

/**
 * Class AddCustomerRequest
 *
 * @pw_element CustomerInfo $customer_info
 * @pw_complex AddCustomerRequest
 */
class AddCustomerRequest{
	/**
	 * @var CustomerInfo
	 */
	public $customer_info;
}

/**
 * Class AddCustomerResponse
 *
 * @pw_element int $i_customer
 * @pw_complex AddCustomerResponse
 */
class AddCustomerResponse{
	/**
	 * @var int
	 */
	public $i_customer;
}

/**
 * Class DeleteCustomerRequest
 *
 * @pw_element int $i_customer
 * @pw_complex DeleteCustomerRequest
 */
class DeleteCustomerRequest{
	/**
	 * @var int
	 */
	public $i_customer;
}

/**
 * Class DeleteCustomerResponse
 *
 * @pw_element boolean $result
 * @pw_complex DeleteCustomerResponse
 */
class DeleteCustomerResponse{
	/**
	 * @var boolean
	 */
	public $result;
}