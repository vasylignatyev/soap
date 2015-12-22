<?php
/**
 * @pw_element int $I_CUSTOMER
 * @pw_element string $EMAIL
 * @pw_element string $PASSWORD
 * @pw_element float $BALANCE
 * @pw_element int $BILLING_MODEL
 * @pw_element boolean $BLOCKED
 * @pw_element boolean $ACTIVE
 * @pw_element date $ISSUE_DATE
 * @pw_element date $FIRST_USAGE
 * @pw_element date $LAST_USAGE
 * @pw_complex CustomerInfo
 */
class CustomerInfo {
    public $I_CUSTOMER;
    public $EMAIL;
    public $PASSWORD;
    public $BALANCE;
    public $BILLING_MODEL;
    public $BLOCKED;
    public $ACTIVE;
    public $ISSUE_DATE;
    public $FIRST_USAGE;
    public $LAST_USAGE;
}
/**
 * @pw_complex CustomerInfoArray
 */
class CustomerInfoArray {
}
/**
 * @pw_element string $VCAM_IP
 * @pw_element string $VCAM_DNAME
 * @pw_element int $VCAM_PORT
 * @pw_element string $VCAM_VIDEO
 * @pw_element string $UTILITY_NAME
 * @pw_element string $UTIL_IN_ARGS
 * @pw_element string $VCAM_PROTOCOL
 * @pw_element string $VCAM_AUDIO
 * @pw_element string $ROD_START_TIME
 * @pw_element string $R_CHUNK_TIME
 * @pw_element string $CONFIG_PORT
 * @pw_element int $EVENT_STATUS
 * @pw_element int $DEL_E_VIDEO
 * @pw_set minoccurs=0
 * @pw_element int $SEND_EMAIL
 * @pw_set minoccurs=0
 * @pw_element int $SEND_SMS
 * @pw_element string $VCAM_LOCATION
 * @pw_complex VcamOptions
 */
class VcamOptions {
	public $VCAM_IP;
	public $VCAM_DNAME;
	public $VCAM_PORT;
	public $VCAM_VIDEO;
	public $UTILITY_NAME;
	public $UTIL_IN_ARGS;
	public $VCAM_PROTOCOL;
	public $VCAM_AUDIO;
	public $ROD_START_TIME;
	public $R_CHUNK_TIME;
	public $CONFIG_PORT;
	public $EVENT_STATUS;
	public $DEL_E_VIDEO;
	public $SEND_EMAIL;
	public $SEND_SMS;
	public $VCAM_LOCATION;
}
/**
 * @pw_element string $HLS_URL
 * @pw_element string $URL
 * @pw_element int $HLS
 * @pw_element int $RTMP
 * @pw_set minoccurs=0
 * @pw_element int $I_CUSTOMER
 * @pw_set minoccurs=0
 * @pw_element string $EMAIL
 * @pw_set minoccurs=0
 * @pw_element string $VENDOR_NAME
 * @pw_set minoccurs=0
 * @pw_element string $VCAM_NAME
 * @pw_set minoccurs=0
 * @pw_element string $SCHEDULE
 * @pw_set minoccurs=0
 * @pw_element string $CUSTOMER_VCAM_NAME
 * @pw_element VcamOptions $OPTIONS
 * @pw_set minoccurs=0
 * @pw_element string $CUSTOMER_VCAM_LOGIN
 * @pw_set minoccurs=0
 * @pw_element string $CUSTOMER_VCAM_PASSWORD
 * @pw_set minoccurs=0
 * @pw_element int $I_CUSTOMER_VCAM
 * @pw_set minoccurs=0
 * @pw_element string $TOKEN
 * @pw_set minoccurs=0
 * @pw_element int $ROD
 * @pw_set minoccurs=0
 * @pw_element int $ROS
 * @pw_set minoccurs=0
 * @pw_element int $ON_AIR
 * @pw_set minoccurs=0
 * @pw_element int $RESTRICTION
 * @pw_set minoccurs=0
 * @pw_element string $TYPE
 * @pw_set minoccurs=0
 * @pw_element string $LAT
 * @pw_set minoccurs=0
 * @pw_element string $LNG
 * @pw_set minoccurs=0
 * @pw_element string $VCAM_LOCATION
 * @pw_complex VcamInfo
 */
class VcamInfo {
    public $HLS_URL;
    public $URL;
    public $HLS;
    public $RTMP;
    public $I_CUSTOMER;
    public $EMAIL;
    public $VENDOR_NAME;
    public $VCAM_NAME;
    public $SCHEDULE;
    public $CUSTOMER_VCAM_NAME;
    public $OPTIONS;
    public $CUSTOMER_VCAM_LOGIN;
    public $CUSTOMER_VCAM_PASSWORD;
    public $I_CUSTOMER_VCAM;
    public $TOKEN;
    public $ROD;
    public $ROS;
    public $ON_AIR;
    public $RESTRICTION;
    public $TYPE;
    public $LAT;
    public $LNG;
    public $VCAM_LOCATION;
}
/**
 * @pw_complex VcamInfoArray
 */
class VcamInfoArray {
}
/**
 * @pw_element string $token
 * @pw_element int $size
 * @pw_complex createVCamThumbnailRequest
 */
class createVCamThumbnailRequest {
	public $token;
	public $size;
}
/**
 * @pw_element string $imageName
 * @pw_complex createVCamThumbnailResponse
 */
class createVCamThumbnailResponse {
	public $imageName;
}
/**
 * @pw_element int $i_customer
 * @pw_complex GetCustomerInfoRequest
 */
class GetCustomerInfoRequest {
	/** @var int */
	public $i_customer;
}
/**
 * @pw_element CustomerInfo $customer_info
 * @pw_complex GetCustomerInfoResponse
 */
class GetCustomerInfoResponse {
	/** @var CustomerInfo */
	public $customer_info;
}
/**
 * @pw_element int $i_customer_vcam
 * @pw_complex GetCustomerVcamRequest
 */
class GetCustomerVcamRequest {
	/** @var int */
	public $i_customer_vcam;
}
/**
 * @pw_element VcamInfo $vcam_info
 * @pw_complex GetCustomerVcamResponse
 */
class GetCustomerVcamResponse {
	/** @var VcamInfo */
	public $vcam_info;
}
/**
 * @pw_element int $i_customer
 * @pw_complex GetCustomerVcamListRequest
 */
class GetCustomerVcamListRequest {
	public $i_customer;
}
/**
 * @pw_element VcamInfoArray $vcam_info_array
 * @pw_complex GetCustomerVcamListResponse
 */
class GetCustomerVcamListResponse {
	public $vcam_info_array;
}
/**
 * @pw_element int $i_customer
 * @pw_complex GetCustomerVcamLimitRequest
 */
class GetCustomerVcamLimitRequest {
	public $i_customer;
}
/**
 * @pw_element int $vcam_limit
 * @pw_element int $current_vcam
 * @pw_complex GetCustomerVcamLimitResponse
 */
class GetCustomerVcamLimitResponse {
	public $vcam_limit;
	public $current_vcam;
}
/**
 * @pw_element int $i_customer
 * @pw_element VcamInfo $vcam_info
 * @pw_complex AddCustomerVcamRequest
 */
class AddCustomerVcamRequest {
	public $i_customer;
	public $vcam_info;
}
/**
 * @pw_element int $i_customer_vcam
 * @pw_complex AddCustomerVcamResponse
 */
class AddCustomerVcamResponse {
	/** @var int */
	public $i_customer_vcam;
}
/**
 * @pw_element int $i_customer_vcam
 * @pw_complex DeleteCustomerVcamRequest
 */
class DeleteCustomerVcamRequest {
	/** @var int */
	public $i_customer_vcam;
}
/**
 * @pw_element VcamInfo $vcam_info
 * @pw_complex DeleteCustomerVcamResponse
 */
class DeleteCustomerVcamResponse {
	/** @var VcamInfo */
	public $vcam_info;
}
/**
 * @pw_element CustomerInfoArray $customer_info_array
 * @pw_complex GetCustomerListResponse
 */
class GetCustomerListResponse {
	public $customer_info_array;
}
/**
 * @pw_element CustomerInfo $customer_info
 * @pw_complex AddCustomerRequest
 */
class AddCustomerRequest {
	public $customer_info;
}
/**
 * @pw_element int $i_customer
 * @pw_complex AddCustomerResponse
 */
class AddCustomerResponse {
	public $i_customer;
}
/**
 * @pw_element int $i_customer
 * @pw_complex DeleteCustomerRequest
 */
class DeleteCustomerRequest {
	public $i_customer;
}
/**
 * @pw_element CustomerInfo $customer_info
 * @pw_complex DeleteCustomerResponse
 */
class DeleteCustomerResponse {
	public $customer_info;
}

