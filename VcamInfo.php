<?php
/**
 * Class VcamOptions
 * @pw_element string $VCAM_IP The Vcam IP address
 * @pw_element string $VCAM_DNAME The Vcam DNS name
 * @pw_element int $VCAM_PORT The Vcam port
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
 * @pw_complex VcamOptions The Vcam Options Structure
 */
class VcamOptions
{
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
 * Class VcamInfo
 * @pw_element string $hls_url hls url
 * @pw_element string $url The Vcam rtmp url
 * @pw_element int $hls The hls port
 * @pw_element int $rtmp The hls port
 * @pw_element int $i_customer The Customer ID
 * @pw_element string $email The Vcam owner email
 * @pw_element string $vendor_name The Vcam Vendor name
 * @pw_element string $vcam_name The Vcam Model
 * @pw_element string $schedule The Vcam schedule
 * @pw_element string $customer_vcam_name The vcam name given by customer
 * @pw_element VcamOptions $options The vcam options
 * @pw_element string $customer_vcam_login The vcam login name
 * @pw_element string $customer_vcam_password The vcam password
 * @pw_element int $i_customer_vcam The Vcam ID
 * @pw_element string $token The Vcam token
 * @pw_element int $rod Record on demand (1-on, 0-off)
 * @pw_element int $ros Record on schedule (1-on, 0-off)
 * @pw_element int $on_air The vcam is online
 * @pw_element int $restriction The vcam Bit Width restriction register
 * @pw_element string $type The Vcam ownership
 * @pw_element string $lat The Vcam latitude
 * @pw_element string $lng The Vcam longitude
 * @pw_element string $vcam_location The Vcam location
 * @pw_complex VcamInfo The Vcam Information Structure
 */
class VcamInfo
{
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
 * Class GetCustomerVcamLimitRequest
 * @pw_element int $i_customer
 * @pw_complex GetCustomerVcamLimitRequest
 */
class GetCustomerVcamLimitRequest
{
    public $i_customer;
}
/**
 * Class GetCustomerVcamLimitResponse
 * @pw_element int $vcam_limit
 * @pw_element int $current_vcam
 * @pw_complex GetCustomerVcamLimitResponse
 */
class GetCustomerVcamLimitResponse
{
    public $vcam_limit;
    public $current_vcam;
}
/**
 * Class VcamInfoArray
 * @pw_complex VcamInfoArray The Array of Vcam Information Structure
 */
class VcamInfoArray {}
/**
 * Class GetCustomerVcamListRequest
 * @pw_element int $i_customer
 * @pw_complex GetCustomerVcamListRequest
 */
class GetCustomerVcamListRequest
{
    public $i_customer;
}
/**
 * Class GetCustomerVcamListResponse
 * @pw_element VcamInfoArray $vcam_info_array
 * @pw_complex GetCustomerVcamListResponse
 */
class GetCustomerVcamListResponse {
    public $vcam_info_array;
}
/**
 * Class AddCustomerVcamRequest
 * @pw_element int $i_customer
 * @pw_element VcamInfo $vcam_info
 * @pw_complex AddCustomerVcamRequest
 */
class AddCustomerVcamRequest {
    public $i_customer;
    public $vcam_info;
}
/**
 * Class AddCustomerVcamResponse
 * @pw_element int $i_customer_vcam
 * @pw_complex AddCustomerVcamResponse
 */
class AddCustomerVcamResponse  {
    /** @var int */
    public $i_customer_vcam;
}
/**
 * Class DeleteCustomerVcamRequest
 * @pw_element int $i_customer_vcam
 * @pw_complex DeleteCustomerVcamRequest
 */
class DeleteCustomerVcamRequest {
	/** @var int */
	public $i_customer_vcam;
}
/**
 * Class DeleteCustomerVcamResponse
 * @pw_element VcamInfo $vcam_info
 * @pw_complex DeleteCustomerVcamResponse
 */
class DeleteCustomerVcamResponse {
	/** @var VcamInfo */
	public $vcam_info;
}