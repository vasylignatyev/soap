<?php
require_once '../soap/ControllerSoap.php';

print( __FILE__ . "\n\n");

$controller = new ControllerSoap();
$controller->set_session_id('746667cbd18b83087bb485c6b5816d80');
//$controller->set_session_id('01234567890123456789012345678901');

	print("/** TEST GET_CUSTOMER_VCAM_LIMIT **/\n");
	$getCustomerVcamLimitRequest = new GetCustomerVcamLimitRequest();
	$getCustomerVcamLimitRequest->i_customer = 56;
	print_r($controller->get_customer_vcam_limit($getCustomerVcamLimitRequest));

	print("/** TEST GET_CUSTOMER_INFO **/\n");
	$getCustomerInfoRequest = new GetCustomerInfoRequest();
	$getCustomerInfoRequest->i_customer = 56;
	$customerInfo = $controller->get_customer_info($getCustomerInfoRequest);
	print_r($customerInfo);

	print("/** TEST GET_CUSTOMER_VCAM **/\n");
	$getCustomerVcamRequest = new GetCustomerVcamRequest();
	$getCustomerVcamRequest->i_customer_vcam = 143;
	$vcamInfo = $controller->get_customer_vcam($getCustomerVcamRequest);
	print_r($vcamInfo);
	
	print("/** TEST GET_CUSTOMER_LIST **/\n");
	$customer_list = $controller->get_customer_list();
	//print_r($customer_list);
	
	
	die();



/* GET CUSTOMER VCAM INFO */
$getCustomerVcamRequest = new GetCustomerVcamRequest();
$getCustomerVcamRequest->i_customer_vcam = 143;
$getCustomerVcamRequest->i_reseller = 1;
$vcamInfo  = $controller->get_customer_vcam($getCustomerVcamRequest);
print_r($vcamInfo);

//$getCustomerVcamListRequest = new GetCustomerVcamListRequest();
//$getCustomerVcamListRequest->i_customer = 56;
//print_r($getCustomerVcamListRequest);
//$customer_vcam_list = $controller->get_customer_vcam_list($getCustomerVcamListRequest);
//print_r($customer_vcam_list);

/*
$addCustomerVcamRequest = new AddCustomerVcamRequest();
$addCustomerVcamRequest->i_customer = 56;
$addCustomerVcamRequest->vcam_info = $customer_vcam_list->vcam_info_array[0];
$addCustomerVcamRequest->vcam_info->customer_vcam_name = 'test1';
print_r($addCustomerVcamRequest);

print_r($controller->add_customer_vcam($addCustomerVcamRequest));
*/