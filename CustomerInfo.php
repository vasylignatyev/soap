<?php
/**
 * Class CustomerInfo
 * @pw_set nillable=false The next element can't be NULL
 * @pw_element int $i_customer The Customer ID
 * @pw_element string $email The Customer email
 * @pw_element string $password The Customer email
 * @pw_element float $balance A User Balance
 * @pw_element int $billing_model The Customer billing model(0 – debit, 1 - credit)
 * @pw_element boolean $blocked The Customer state: 0 – Customer blocked, 1 – Customer unblocked
 * @pw_element boolean $active The Customer state: 0 - not active, 1 - active
 * @pw_element date $issue_date Date when the customer was created
 * @pw_element date $first_usage Date when the customer was first logged in
 * @pw_element date $last_usage Date when the customer was last logged in
 * @pw_complex CustomerInfo The Customer Information Structure
 */
class CustomerInfo
{
    /**
     * The Customer ID
     *
     * @var int
     */
    public $i_customer = 56;
    /**
     * The Customer email
     *
     * @var string
     */
    public $email = 'vignatyev@list.ru';
    /**
     * The Customer password
     *
     * @var string
     */
    public $password;
    /**
     * The Customer balance
     *
     * @var float
     */
    public $balance = 1.11;
    /**
     * The Customer billing model(0 – debit, 1 - credit)
     *
     * @var int
     */
    public $billing_model = 0;
    /**
     * The Customer state: 0 – Customer blocked, 1 – Customer unblocked
     *
     * @var boolean
     */
    public $blocked = 0;
    /**
     * The Customer state: 0 - not active, 1 - active
     *
     * @var boolean
     */
    public $active = 1;
    /**
     * The Customer issue date
     *
     * @var date
     */
    public $issue_date = '1966-07-03 06:00:00';
    /**
     * Date when the customer was first logged in
     *
     * @var date
     */
    public $first_usage = '1966-07-03 07:00:00';
    /**
     * Date when the customer was last logged in
     *
     * @var date
     */
    public $last_usage = '1966-07-03 08:00:00';
}
/**
 * Class CustomerInfoArray
 * @pw_complex CustomerInfoArray The Array of Customer Information Structure
 */
class CustomerInfoArray{}
/**
 * Class GetCustomerListResponse
 * @pw_element CustomerInfoArray $customer_info_array
 * @pw_complex GetCustomerListResponse
 */
class GetCustomerListResponse{
    /**
     * The Customer Info Array
     *
     * @var CustomerInfoArray
     */
    public $customer_info_array;
}
/**
 * Class GetCustomerInfoRequest
 *
 * @pw_element int $i_customer The Customer ID
 * @pw_complex GetCustomerInfoRequest
 */
class GetCustomerInfoRequest {
    public $i_customer;
}
/**
 * Class GetCustomerInfoResponse
 *
 * @pw_element CustomerInfo $customer_info
 * @pw_complex GetCustomerInfoResponse
 */
class GetCustomerInfoResponse {
    /**
     * @var CustomerInfo
     */
    public $customer_info;
}
/**
 * Class AddCustomerRequest
 *
 * @pw_element CustomerInfo $customer_info
 * @pw_complex AddCustomerRequest
 */
class AddCustomerRequest {
    public $customer_info;
}
/**
 * Class AddCustomerResponse
 *
 * @pw_element int $i_customer
 * @pw_complex AddCustomerResponse
 */
class AddCustomerResponse {
    /**
     * @var int
     */
    public $i_customer;
}
/**
 * Class DeleteCustomerRequest
 *
 * @pw_element int $i_customer
 * @pw_complex DeleteCustomerRequest
 */
class DeleteCustomerRequest {
    public $i_customer;
}
/**
 * Class DeleteCustomerResponse
 *
 * @pw_element boolean $result
 * @pw_complex DeleteCustomerResponse
 */
class DeleteCustomerResponse {
    /**
     * @var boolean
     */
    public $result;
}
