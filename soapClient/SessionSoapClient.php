<?php
/**
 * @service SessionSoapClient
 */
class SessionSoapClient{
	/**
	 * The WSDL URI
	 *
	 * @var string
	 */
	public static $_WsdlUri='http://localhost/soap/SessionSoap.php?WSDL';
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
	 * Login to VHOME SERVER
	 *
	 * @param LoginRequest $loginRequest
	 * @return LoginResponse
	 */
	public function login($loginRequest){
		return self::_Call('login',Array(
			$loginRequest
		));
	}
}

/**
 * Class LoginRequest
 *
 * @pw_element string $user_name User Name
 * @pw_element string $user_pass A string with a user password
 * @pw_complex LoginRequest
 */
class LoginRequest{
	/**
	 * User Name
	 *
	 * @var string
	 */
	public $user_name;
	/**
	 * A string with a user password
	 *
	 * @var string
	 */
	public $user_pass;
}

/**
 * Class LoginResponse
 *
 * @pw_element string $session_id A string with a session identification
 * @pw_complex LoginResponse
 */
class LoginResponse{
	/**
	 * A string with a session identification
	 *
	 * @var string
	 */
	public $session_id;
}
