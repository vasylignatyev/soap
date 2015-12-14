<?php 
	require_once 'Functions.php';
	
	Functions::set_session_id('746667cbd18b83087bb485c6b5816d80');
	$getCustomerVcamLimitRequest = new GetCustomerVcamLimitRequest();
	$getCustomerVcamLimitRequest->i_customer = 56;
	$getCustomerVcamLimitRequest->i_reseller = 1;
	print_r(Functions::get_customer_vcam_limit($getCustomerVcamLimitRequest));
	
	
	$getCustomerVcamListRequest = new GetCustomerVcamListRequest();
	$getCustomerVcamListRequest->i_customer = 56;
	$getCustomerVcamListRequest->i_reseller = 1;
	
	$addCustomerVcamRequest = new AddCustomerVcamRequest();
	//$addCustomerVcamRequest->i_customer = 56;
	$addCustomerVcamRequest->i_reseller = 1;
	$vcam_list = Functions::get_customer_vcam_list($getCustomerVcamListRequest);
	print_r($vcam_list[0]);
	$addCustomerVcamRequest->i_reseller = 1;
	$addCustomerVcamRequest->vcam_info = $vcam_list[0];
	$addCustomerVcamRequest->vcam_info->CUSTOMER_VCAM_NAME = 'test1';
	print_r($addCustomerVcamRequest);
	print_r(Functions::add_customer_vcam($addCustomerVcamRequest));
			
?>