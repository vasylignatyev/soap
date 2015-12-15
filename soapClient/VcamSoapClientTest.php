<?php
require_once 'VcamSoapClient.php';


$vcamSoapClient = new VcamSoapClient();
var_dump($vcamSoapClient->set_session_id('746667cbd18b83087bb485c6b5816d80'));

$getCustomerVcamListRequest = new GetCustomerVcamListRequest();
$getCustomerVcamListRequest->i_customer = 56;

$addCustomerVcamRequest = new AddCustomerVcamRequest();
$addCustomerVcamRequest->i_customer = 56;
$addCustomerVcamRequest->vcam_info = $vcamSoapClient->get_customer_vcam_list($getCustomerVcamListRequest)->vcam_info_array[0];
$addCustomerVcamRequest->vcam_info->customer_vcam_name = 'test1';
print_r($addCustomerVcamRequest);

print_r($vcamSoapClient->add_customer_vcam($addCustomerVcamRequest));

