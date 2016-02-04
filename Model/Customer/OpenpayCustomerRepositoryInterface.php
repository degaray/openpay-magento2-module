<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 30/12/15
 * Time: 02:55 PM
 */

namespace Degaray\Openpay\Model\Customer;

use Magento\Customer\Api\Data\CustomerInterface;

/**
 * Interface OpenpayCustomerRepositoryInterface
 * @package Degaray\Openpay\Model\Customer
 */
interface OpenpayCustomerRepositoryInterface
{
    /**
     * @param CustomerInterface $customer
     * @return \Openpay\Client\Type\OpenpayCustomerType
     */
    public function save(CustomerInterface $customer);

    /**
     * @param $customerId
     * @return \Openpay\Client\Type\OpenpayCustomerType
     */
    public function get($customerId);
}
