<?php
/**
 * @service VcamSoapClient
 */
class VcamSoapClient{
	/**
	 * The WSDL URI
	 *
	 * @var string
	 */
	public static $_WsdlUri='http://localhost/soap/VcamSoap.php?WSDL';
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
	 * Get Customer Vcam Limit
	 *
	 * @param GetCustomerVcamLimitRequest $args
	 * @return GetCustomerVcamLimitResponse
	 */
	public function get_customer_vcam_limit($args){
		return self::_Call('get_customer_vcam_limit',Array(
			$args
		));
	}

	/**
	 * Get Customer Vcam
	 *
	 * @param GetCustomerVcamListRequest $args
	 * @return GetCustomerVcamListResponse
	 */
	public function get_customer_vcam_list($args){
		return self::_Call('get_customer_vcam_list',Array(
			$args
		));
	}

	/**
	 * Get Customer Vcam
	 *
	 * @param AddCustomerVcamRequest $args
	 * @return AddCustomerVcamResponse
	 */
	public function add_customer_vcam($args){
		return self::_Call('add_customer_vcam',Array(
			$args
		));
	}
}

/**
 * Class VcamInfo
 *
 * @pw_element string $hls_url hls url
 * @pw_element string $url The Vcam rtmp url
 * @pw_element int $hls The hls port
 * @pw_element int $rtmp The hls port
 * @pw_element int $i_customer The Customer ID
 * @pw_element string $email The Vcam owner email
 * @pw_element string $vendor_name The Vcam Vendor name
 * @pw_element string $vcam_name The Vcam Model
 * @pw_element string $schedule The Vcam schedule
 * @pw_element string $customer_vcam_name The vcam name given by customer
 * @pw_element VcamOptions $options The vcam options
 * @pw_element string $customer_vcam_login The vcam login name
 * @pw_element string $customer_vcam_password The vcam password
 * @pw_element int $i_customer_vcam The Vcam ID
 * @pw_element string $token The Vcam token
 * @pw_element int $rod Record on demand (1-on, 0-off)
 * @pw_element int $ros Record on schedule (1-on, 0-off)
 * @pw_element int $on_air The vcam is online
 * @pw_element int $restriction The vcam Bit Width restriction register
 * @pw_element string $type The Vcam ownership
 * @pw_element string $lat The Vcam latitude
 * @pw_element string $lng The Vcam longitude
 * @pw_element string $vcam_location The Vcam location
 * @pw_complex VcamInfo
 */
class VcamInfo{
	/**
	 * hls url
	 *
	 * @var string
	 */
	public $hls_url;
	/**
	 * The Vcam rtmp url
	 *
	 * @var string
	 */
	public $url;
	/**
	 * The hls port
	 *
	 * @var int
	 */
	public $hls;
	/**
	 * The hls port
	 *
	 * @var int
	 */
	public $rtmp;
	/**
	 * The Customer ID
	 *
	 * @var int
	 */
	public $i_customer;
	/**
	 * The Vcam owner email
	 *
	 * @var string
	 */
	public $email;
	/**
	 * The Vcam Vendor name
	 *
	 * @var string
	 */
	public $vendor_name;
	/**
	 * The Vcam Model
	 *
	 * @var string
	 */
	public $vcam_name;
	/**
	 * The Vcam schedule
	 *
	 * @var string
	 */
	public $schedule;
	/**
	 * The vcam name given by customer
	 *
	 * @var string
	 */
	public $customer_vcam_name;
	/**
	 * The vcam options
	 *
	 * @var VcamOptions
	 */
	public $options;
	/**
	 * The vcam login name
	 *
	 * @var string
	 */
	public $customer_vcam_login;
	/**
	 * The vcam password
	 *
	 * @var string
	 */
	public $customer_vcam_password;
	/**
	 * The Vcam ID
	 *
	 * @var int
	 */
	public $i_customer_vcam;
	/**
	 * The Vcam token
	 *
	 * @var string
	 */
	public $token;
	/**
	 * Record on demand (1-on, 0-off)
	 *
	 * @var int
	 */
	public $rod;
	/**
	 * Record on schedule (1-on, 0-off)
	 *
	 * @var int
	 */
	public $ros;
	/**
	 * The vcam is online
	 *
	 * @var int
	 */
	public $on_air;
	/**
	 * The vcam Bit Width restriction register
	 *
	 * @var int
	 */
	public $restriction;
	/**
	 * The Vcam ownership
	 *
	 * @var string
	 */
	public $type;
	/**
	 * The Vcam latitude
	 *
	 * @var string
	 */
	public $lat;
	/**
	 * The Vcam longitude
	 *
	 * @var string
	 */
	public $lng;
	/**
	 * The Vcam location
	 *
	 * @var string
	 */
	public $vcam_location;
}

/**
 * Class VcamOptions
 *
 * @pw_element string $vcam_ip The Vcam IP address
 * @pw_element string $vcam_dname The Vcam DNS name
 * @pw_element int $vcam_port The Vcam port
 * @pw_complex VcamOptions
 */
class VcamOptions{
	/**
	 * The Vcam IP address
	 *
	 * @var string
	 */
	public $vcam_ip;
	/**
	 * The Vcam DNS name
	 *
	 * @var string
	 */
	public $vcam_dname;
	/**
	 * The Vcam port
	 *
	 * @var int
	 */
	public $vcam_port;
}

/**
 * Class GetCustomerVcamLimitRequest
 *
 * @pw_element int $i_customer
 * @pw_complex GetCustomerVcamLimitRequest
 */
class GetCustomerVcamLimitRequest{
	/**
	 * @var int
	 */
	public $i_customer;
}

/**
 * Class GetCustomerVcamLimitResponse
 *
 * @pw_element int $vcam_limit
 * @pw_element int $current_vcam
 * @pw_complex GetCustomerVcamLimitResponse
 */
class GetCustomerVcamLimitResponse{
	/**
	 * @var int
	 */
	public $vcam_limit;
	/**
	 * @var int
	 */
	public $current_vcam;
}


/**
 * Class GetCustomerVcamListRequest
 *
 * @pw_element int $i_customer
 * @pw_complex GetCustomerVcamListRequest
 */
class GetCustomerVcamListRequest{
	/**
	 * @var int
	 */
	public $i_customer;
}

/**
 * Class GetCustomerVcamListResponse
 *
 * @pw_element VcamInfoArray $vcam_info_array
 * @pw_complex GetCustomerVcamListResponse
 */
class GetCustomerVcamListResponse{
	/**
	 * @var VcamInfoArray
	 */
	public $vcam_info_array;
}

/**
 * Class AddCustomerVcamRequest
 *
 * @pw_element int $i_customer
 * @pw_element VcamInfo $vcam_info
 * @pw_complex AddCustomerVcamRequest
 */
class AddCustomerVcamRequest{
	/**
	 * @var int
	 */
	public $i_customer;
	/**
	 * @var VcamInfo
	 */
	public $vcam_info;
}

/**
 * Class AddCustomerVcamResponse
 *
 * @pw_element int $i_customer_vcam
 * @pw_complex AddCustomerVcamResponse
 */
class AddCustomerVcamResponse{
	/**
	 * @var int
	 */
	public $i_customer_vcam;
}