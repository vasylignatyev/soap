<?php

/**
 * Created by PhpStorm.
 * User: vignatyev
 * Date: 01.12.2015
 * Time: 15:21
 */

//require_once('VcamInfo.php');
require_once 'Functions.php';

class Vcam
{
    /**
     * @var PDO
     */
    private $pdo = null;
    /**
     * @var int
     */
    private $resellerId = 0;
    /**
     * @var int
     */
    private $sessionId = 0;
    function __construct()
    {
        $this->__wakeup();
    }
    public function __wakeup()
    {
        /*
        $this->pdo = new PDO("mysql:host=localhost;dbname=virtualhome","vh_web", '6EusrWvUBHKJQnQF',array( PDO::ATTR_PERSISTENT => true));
        $this->pdo->exec("SET NAMES UTF8");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        */
    }
    public function __sleep() {
        return array("sessionId",'resellerId');
    }
    /**
     * @throws Exception
     */
    private function isLoggedIn() {
        if(empty($this->resellerId)){
            throw new Exception('Not authorized');
        }
    }
    private function checkICustomer($iCustomer){
        Functions::checkICustomer($iCustomer, $this->resellerId);
    }
    /**
     * Set Session Id
     *
     * @param string $session_id
     * @return boolean
     */
    public function set_session_id($session_id)
    {
        try {
            $this->resellerId = Functions::set_session_id($session_id);
            $this->sessionId = $session_id;
        } catch (Exception $e) {
            return (new SoapFault('Customer SOAP Server.', $e->getMessage()));
        }
    }
    /**
     * Get Customer Vcam Limit
     *
     * @param GetCustomerVcamLimitRequest $args
     * @return GetCustomerVcamLimitResponse
     */
    function get_customer_vcam_limit($args)
    {
        try {
            $this->isLoggedIn();
            $args->i_reseller = $this->resellerId;
            return Functions::get_customer_vcam_limit($args);
        } catch (Exception $e) {
            return (new SoapFault('Customer SOAP Server.', $e->getMessage()));
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
            $this->isLoggedIn();
            $args->i_reseller = $this->resellerId;
            $getCustomerVcamListResponse = new GetCustomerVcamListResponse();

            $customerVcamList =  Functions::get_customer_vcam_list($args);

            foreach($customerVcamList as $vcamInfo)
                $getCustomerVcamListResponse->vcam_info_array[] = array_change_key_case($vcamInfo);

            return $getCustomerVcamListResponse;
        } catch (Exception $e) {
            return (new SoapFault('Customer SOAP Server.', $e->getMessage()));
        }
    }
    /**
     * Get Customer Vcam
     *
     * @param AddCustomerVcamRequest $args
     * @return AddCustomerVcamResponse
     */
    function add_customer_vcam($args) {
        try {
            $this->isLoggedIn();
            $args->i_reseller = $this->resellerId;
            return(Functions::add_customer_vcam($args));
        } catch (Exception $e) {
            return (new SoapFault('Customer SOAP Server.', $e->getMessage()));
        }

        return true;
    }
}
