<?php 

require_once '../controller/Controller.php';
	print( __FILE__ . "\n\n");
try {		
	Controller::set_session_id('746667cbd18b83087bb485c6b5816d80');
	
	print("/** TEST GET_CUSTOMER_VCAM_LIMIT **/");
	$getCustomerVcamLimitRequest = new GetCustomerVcamLimitRequest();
	$getCustomerVcamLimitRequest->i_customer = 56;
	$getCustomerVcamLimitRequest->i_reseller = 1;
	print_r(Controller::get_customer_vcam_limit($getCustomerVcamLimitRequest));

	print( "/* TEST GET_CUSTOMER_INFO */\n");
	$getCustomerInfoRequest = new GetCustomerInfoRequest();
	$getCustomerInfoRequest->i_customer = 56;
	$customerInfo = Controller::get_customer_info($getCustomerInfoRequest);
	print_r($customerInfo);
	
	print("/** TEST GET_CUSTOMER_LIST **/\n");
	$customer_list = Controller::get_customer_list();
	print_r($customer_list);

	print("/** TEST ADD_CUSTOMER **/\n");
	$addCustomerRequest = new AddCustomerRequest();
	$customerInfo->EMAIL .= "1";
	$addCustomerRequest->customer_info = $customerInfo;
	$iCustomer = Controller::add_customer($addCustomerRequest);
	print_r($iCustomer . "\n");

	if(!empty($iCustomer)) {
		print("/** TEST DELETE_CUSTOMER **/\n");
		$deleteCustomerRequest = new DeleteCustomerRequest();
		$deleteCustomerRequest->i_customer = $iCustomer;
		$customer_info = Controller::delete_customer($deleteCustomerRequest);
		print_r($customer_info);
	}
	
	die();	

}catch (Exception $e){
	print_r($e);
}
	/* GET CUSTOMER VCAM LIST */
	$getCustomerVcamListRequest = new GetCustomerVcamListRequest();
	$getCustomerVcamListRequest->i_customer = 56;
	$getCustomerVcamListRequest->i_reseller = 1;
	
	/*  ADD CUSTOMER
	$addCustomerVcamRequest = new AddCustomerVcamRequest();
	//$addCustomerVcamRequest->i_customer = 56;
	$addCustomerVcamRequest->i_reseller = 1;
	$vcam_list = Controller::get_customer_vcam_list($getCustomerVcamListRequest);
	print_r($vcam_list);
	$addCustomerVcamRequest->i_reseller = 1;
	$addCustomerVcamRequest->vcam_info = $vcam_list[0];
	$addCustomerVcamRequest->vcam_info->CUSTOMER_VCAM_NAME = 'test1';
	print_r($addCustomerVcamRequest);
	print_r(Controller::add_customer_vcam($addCustomerVcamRequest));
	*/		
?>