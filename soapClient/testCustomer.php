<?php
require_once('CustomerSoapClient.php');


$customerSoapClient = new CustomerSoapClient();
var_dump($customerSoapClient->set_session_id('01234567890123456789012345678901'));
var_dump( $customerSoapClient->get_customer_list() );
