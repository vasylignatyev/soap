<?php
/**
 * Created by PhpStorm.
 * User: vignatyev
 * Date: 01.12.2015
 * Time: 16:45
 */
require_once 'Vcam.php';

$vcam = new Vcam();
$vcam->set_session_id('746667cbd18b83087bb485c6b5816d80');
//$vcam->set_session_id('01234567890123456789012345678901');

$getCustomerVcamLimitRequest = new GetCustomerVcamLimitRequest();
$getCustomerVcamLimitRequest->i_customer = 56;
//print_r($vcam->get_customer_vcam_limit($getCustomerVcamLimitRequest));
//print_r($vcam->get_customer_vcam_list($getCustomerVcamLimitRequest));


$getCustomerVcamListRequest = new GetCustomerVcamListRequest();
$getCustomerVcamListRequest->i_customer = 56;

$addCustomerVcamRequest = new AddCustomerVcamRequest();
$addCustomerVcamRequest->i_customer = 56;
$addCustomerVcamRequest->vcam_info = $vcam->get_customer_vcam_list($getCustomerVcamListRequest)->vcam_info_array[0];
$addCustomerVcamRequest->vcam_info->customer_vcam_name = 'test1';
print_r($addCustomerVcamRequest);

print_r($vcam->add_customer_vcam($addCustomerVcamRequest));
