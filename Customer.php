<?php
/**
 * Created by PhpStorm.
 * User: vignatyev
 * Date: 23.11.2015
 * Time: 7:48
 */
require_once('CustomerInfo.php');

class Customer
{
    static $customerInfoSelect = "SELECT t3.I_CUSTOMER 'i_customer', t3.EMAIL 'email', t3.PASSWORD 'password', t3.BALANCE 'balance',
        t3.BILLING_MODEL 'billing_model', t3.BLOCKED 'blocked', t3.ACTIVE 'active', t3.ISSUE_DATE 'issue_date',
        t3.FIRST_USAGE 'first_usage', t3.LAST_USAGE 'last_usage' FROM customer t3
        INNER JOIN reseller t26 ON t3.I_RESELLER = t26.I_RESELLER";

    static $customerInfoArray = array('i_customer','email','password','balance','billing_model','blocked',
        'active','issue_date','first_usage','last_usage');

    private $pdo = null;
    private $resellerId = null;
    private $sessionId = null;

    function __construct()
    {
        $this->__wakeup();
    }
    public function __wakeup()
    {
        $this->pdo = new PDO("mysql:host=localhost;dbname=virtualhome","vh_web", '6EusrWvUBHKJQnQF',array( PDO::ATTR_PERSISTENT => true));
        $this->pdo->exec("SET NAMES UTF8");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
    /**
     * Set Session Id
     *
     * @param string $session_id
     * @return boolean
     */
    public function set_session_id($session_id)
    {
        try {
            //throw new Exception($session_id);
            $sqlStr = "SELECT I_RESELLER FROM reseller WHERE TOKEN = '$session_id'";

            $reseller_id = intval($this->pdo->query($sqlStr)->fetchColumn());

            if(!empty($reseller_id)) {
                $this->sessionId = $session_id;
                $this->resellerId = $reseller_id;
                //throw new Exception($this->sessionId);

                return true;
            } else {
                throw new Exception('Not authorized');
            }
         } catch (Exception $e) {
            return (new SoapFault('Customer SOAP Server.', $e->getMessage()));
        }
    }

    /**
     * Get Customer Info List
     *
     * @return GetCustomerListResponse
     */
    public function get_customer_list()
    {
        try {
            $this->isLoggedIn();

            $sqlStr = self::$customerInfoSelect . " WHERE t26.TOKEN = :session_id";

            $sth = $this->pdo->prepare($sqlStr);
            $sth->bindParam(':session_id', $this->sessionId);
            $sth->execute();
            $customerList = $sth->fetchAll(PDO::FETCH_ASSOC);
            return array('customer_info_array' => $customerList);
        } catch (Exception $e) {
            return (new SoapFault('Customer SOAP Server.', $e->getMessage()));
        }
    }

    /**
     * Get Customer Info
     *
     * @param GetCustomerInfoRequest $getCustomerInfoRequest
     * @return GetCustomerInfoResponse
     */
    public function get_customer_info($getCustomerInfoRequest)
    {
        try {
            $this->isLoggedIn();

            $args = array('i_customer');
            foreach ($args as $arg) {
                if (empty($getCustomerInfoRequest->$arg)) {
                    throw new Exception("Argument '$arg' is empty");
                }
                $$arg = $getCustomerInfoRequest->$arg;
            }

            $sqlStr = self::$customerInfoSelect .
                " WHERE t26.TOKEN = :session_id AND t3.I_CUSTOMER = :i_customer";

            $sth = $this->pdo->prepare($sqlStr);
            $sth->bindParam(':session_id', $this->sessionId);
            $sth->bindParam(':i_customer', $i_customer);
            $sth->execute();
            $customerInfo = $sth->fetch(PDO::FETCH_ASSOC);
            return array('customer_info' => $customerInfo);
        } catch (Exception $e) {
            return (new SoapFault('Customer SOAP Server.', $e->getMessage()));
        }

    }

    /**
     * Add Customer
     *
     * @param AddCustomerRequest $addCustomerRequest
     * @return AddCustomerResponse
     */
    public function add_customer($addCustomerRequest)
    {
        $customer_info = null;
        $args = array('customer_info');
        try {
            $this->isLoggedIn();

            foreach ($args as $arg) {
                if (empty($addCustomerRequest->$arg)) {
                    throw new Exception("Argument '$arg' is empty");
                }
                $$arg = $addCustomerRequest->$arg;
            }

            $args = array('email', 'password');
            foreach ($args as $arg) {
                if (empty($customer_info->$arg)) {
                    throw new Exception("required parameter '$arg' is missing in customer_info parametr");
                }
                $$arg = $customer_info->$arg;
            }
            $sqlStr = "INSERT INTO customer (I_RESELLER,EMAIL,PASSWORD) VALUES (?,?,?)";
            $sth = $this->pdo->prepare($sqlStr);
            $sth->execute(array($this->resellerId, $email, $password));
            $iCustomer = $this->pdo->lastInsertId();

            $addCustomerResponse = new AddCustomerResponse();
            $addCustomerResponse->i_customer = $iCustomer;

            return ($addCustomerResponse);
        } catch (Exception $e) {
            return (new SoapFault('Customer SOAP Server.', $e->getMessage()));
        }
    }

    /**
     * Delete Customer
     *
     * @param DeleteCustomerRequest $deleteCustomerRequest
     * @return DeleteCustomerResponse
     */
    public function delete_customer($deleteCustomerRequest)
    {
        try {
            $session_id = null;
            $i_customer = null;

            $this->isLoggedIn();

            $args = array('i_customer');
            foreach ($args as $arg) {
                if (empty($deleteCustomerRequest->$arg)) {
                    throw new Exception("Argument '$arg' is empty");
                }
                $$arg = $deleteCustomerRequest->$arg;
            }

            $sqlStr = "DELETE FROM customer WHERE I_CUSTOMER = ? AND I_RESELLER = ?";
            $sth = $this->pdo->prepare($sqlStr);
            $sth->execute(array($i_customer, $this->resellerId));
            $rowCount = $sth->rowCount();

            $deleteCustomerResponse = new DeleteCustomerResponse();
            $deleteCustomerResponse->result = $rowCount;

            return ($deleteCustomerResponse);
        } catch (Exception $e) {
            return (new SoapFault('Customer SOAP Server.', $e->getMessage()));
        }
    }
}
//$customer = new Customer();
//$customer->set_session_id('01234567890123456789012345678901');
//var_dump($customer->get_customer_list());
/*
$getCustomerInfoRequest = new GetCustomerInfoRequest();
$getCustomerInfoRequest->i_customer = 271;
var_dump( $customer->get_customer_info($getCustomerInfoRequest) );
*/
/*
$addCustomerRequest = new AddCustomerRequest();
$customerInfo = new CustomerInfo();
$customerInfo->email = 'vasyl14.ignatyev@gmail.com';
$customerInfo->password = '123456';
$addCustomerRequest->customer_info = $customerInfo;
print_r($customer->add_customer($addCustomerRequest));
*/
/*
$deleteCustomerRequest = new DeleteCustomerRequest();
$deleteCustomerRequest->i_customer = 275;
print_r($customer->delete_customer($deleteCustomerRequest));
*/
