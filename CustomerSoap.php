<?php
/**
 * Created by PhpStorm.
 * User: vignatyev
 * Date: 23.11.2015
 * Time: 8:40
 */
require_once('Customer.php');
//require_once('CustomerInfo.php');

//require_once('php-wsdl/class.phpwsdl.php');

session_start();

try {
    //$server = new SoapServer('CustomerSoap.wsdl');
    $server = new SoapServer('http://localhost/soap/CustomerService.php?WSDL');
    $server->setClass('Customer');
    $server->setPersistence(SOAP_PERSISTENCE_SESSION);
    $server->handle();
} catch(SoapFault $e) {
    error_log("SOAP ERROR: ". $e->getMessage());
}