<?php
require_once '../controller/Controller.php';
class ControllerSoap {
	
	/** @var PDO */
	private $pdo = null;
	
	/** @var int */
	private $resellerId = 0;
	
	/** @var int */
	private $sessionId = 0;
	function __construct() {
		$this->__wakeup ();
	}
	public function __wakeup() {
		Controller::setResselerId ( $this->resellerId );
		Controller::setSessionId( $this->sessionId );
		
		$this->pdo = new PDO ( "mysql:host=localhost;dbname=virtualhome", "vh_web", '6EusrWvUBHKJQnQF', array (
				PDO::ATTR_PERSISTENT => true 
		) );
		$this->pdo->exec ( "SET NAMES UTF8" );
		$this->pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	// public function __
	public function __sleep() {
		return array (
				"sessionId",
				'resellerId' 
		);
	}
	private function checkICustomer($iCustomer) {
		Controller::checkICustomer ( $iCustomer, $this->resellerId );
	}
	/**
	 * Set Session Id
	 *
	 * @param string $session_id        	
	 * @return boolean
	 */
	public function set_session_id($session_id) {
		try {
			$this->resellerId = Controller::set_session_id ( $session_id );
			$this->sessionId = $session_id;
		} catch ( Exception $e ) {
			return (new SoapFault ( 'Customer SOAP Server.', $e->getMessage () ));
		}
	}
	/**
	 * Get Customer Vcam Limit
	 *
	 * @param GetCustomerVcamLimitRequest $args        	
	 * @return GetCustomerVcamLimitResponse
	 */
	function get_customer_vcam_limit($args) {
		try {
			$args->i_reseller = $this->resellerId;
			return Controller::get_customer_vcam_limit ( $args );
		} catch ( Exception $e ) {
			return (new SoapFault ( 'Customer SOAP Server.', $e->getMessage () ));
		}
	}
	/**
	 * Get Customer Video Camera Info
	 *
	 * @param GetCustomerVcamRequest $args        	
	 * @return GetCustomerVcamResponse
	 */
	function get_customer_vcam($args) {
		try {
			// throw new Exception(var_export($args, true));
			$getCustomerVcamResponse = new GetCustomerVcamResponse ();
			$getCustomerVcamResponse->vcam_info = Controller::get_customer_vcam ( $args );
			// throw new Exception(var_export($getCustomerVcamResponse, true));
			return ($getCustomerVcamResponse);
		} catch ( Exception $e ) {
			return (new SoapFault ( __METHOD__, $e->getMessage () ));
		}
	}
	/**
	 * Get Customer Vcam
	 *
	 * @param GetCustomerVcamListRequest $args        	
	 * @return GetCustomerVcamListResponse
	 */
	function get_customer_vcam_list($args) {
		try {
			$getCustomerVcamListResponse = new GetCustomerVcamListResponse ();
			$getCustomerVcamListResponse->vcam_info_array = Controller::get_customer_vcam_list ( $args );
			return $getCustomerVcamListResponse;
		} catch ( Exception $e ) {
			return (new SoapFault ( __METHOD__, $e->getMessage () ));
		}
	}
	/**
	 * Add Customer Vcam
	 *
	 * @param AddCustomerVcamRequest $args        	
	 * @return AddCustomerVcamResponse
	 */
	function add_customer_vcam($args) {
		try {
			$args->i_reseller = $this->resellerId;
			return (Controller::add_customer_vcam ( $args ));
		} catch ( Exception $e ) {
			return (new SoapFault ( 'Customer SOAP Server.', $e->getMessage () ));
		}
		
		return true;
	}
	/**
	 * Delete Customer Vcam
	 *
	 * @param DeleteCustomerVcamRequest $args        	
	 * @return DeleteCustomerVcamResponse
	 */
	function delete_customer_vcam($args) {
		try {
		} catch ( Exception $e ) {
			return (new SoapFault ( __METHOD__, $e->getMessage () ));
		}
	}
	/**
	 * Get Customer Customer Info
	 *
	 * @param GetCustomerInfoRequest $args        	
	 * @return GetCustomerInfoResponse
	 */
	function get_customer_info($args) {
		try {
			/**  @var GetCustomerInfoResponse */
			$result = new GetCustomerInfoResponse ();
			$result->customer_info = Controller::get_customer_info ( $args );
			return $result;
		} catch ( Exception $e ) {
			return (new SoapFault ( __METHOD__, $e->getMessage () ));
		}
	}
	/**
	 * Get CustomerInfo List
	 *
	 * @return GetCustomerListResponse
	 */
	function get_customer_list() {
		try {
			$result = new GetCustomerListResponse ();
			$result->customer_info_array = Controller::get_customer_list ();
			
			return $result;
		} catch ( Exception $e ) {
			return (new SoapFault ( __METHOD__, $e->getMessage () ));
		}
	}
	/**
	 * Add Customer
	 *
	 * @param AddCustomerRequest $request
	 * @return int
	 */
	static function add_customer($request) {
		try {
			$result = new AddCustomerResponse();
			$result->i_customer = Controller::add_customer ($request);
				
			return $result;
		} catch ( Exception $e ) {
			return (new SoapFault ( __METHOD__, $e->getMessage () ));
		}
		
	}
}
	