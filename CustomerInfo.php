<?php
/**
 * @pw_element int $i_customer
 * @pw_element string $email
 * @pw_element string $password
 * @pw_element float $balance
 * @pw_element int $billing_model
 * @pw_element boolean $blocked
 * @pw_element boolean $active
 * @pw_element date $issue_date
 * @pw_element date $first_usage
 * @pw_element date $last_usage
 * @pw_complex CustomerInfo
 */
class CustomerInfo
{
    public $i_customer;
    public $email;
    public $password;
    public $balance;
    public $billing_model;
    public $blocked;
    public $active;
    public $issue_date;
    public $first_usage;
    public $last_usage;
}
/**
 * @pw_complex CustomerInfoArray
 */
class CustomerInfoArray{}
/**
 * @pw_element CustomerInfoArray
 * @pw_complex GetCustomerListResponse
 */
class GetCustomerListResponse{
    public $customer_info_array;
}
/**
 * @pw_element int $i_customer
 * @pw_complex GetCustomerInfoRequest
 */
class GetCustomerInfoRequest {
    public $i_customer;
}
/**
 * @pw_element CustomerInfo $customer_info
 * @pw_complex GetCustomerInfoResponse
 */
class GetCustomerInfoResponse {
	public $customer_info;
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
 * @pw_element boolean $result
 * @pw_complex DeleteCustomerResponse
 */
class DeleteCustomerResponse {
    public $result;
}
