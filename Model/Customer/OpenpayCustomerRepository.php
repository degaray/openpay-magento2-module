<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 30/12/15
 * Time: 02:43 PM
 */

namespace Degaray\Openpay\Model\Customer;

use Magento\Customer\Api\Data\CustomerInterface;
use Openpay\Client\Adapter\OpenpayCustomerAdapterInterface;

class OpenpayCustomerRepository implements OpenpayCustomerRepositoryInterface
{
    const CUSTOMER_REQUIRES_ACCOUNT = true;

    /**
     * @var OpenpayCustomerAdapterInterface
     */
    protected $customerAdapter;

    /**
     * OpenpayCustomerRepository constructor.
     * @param OpenpayCustomerAdapterInterface $customerAdapter
     */
    public function __construct(OpenpayCustomerAdapterInterface $customerAdapter)
    {
        $this->customerAdapter = $customerAdapter;
    }

    /**
     * @param CustomerInterface $customer
     * @return \Openpay\Client\Type\OpenpayCustomerType
     */
    public function save(CustomerInterface $customer)
    {
        $params = [
            'name' => $customer->getFirstname(),
            'last_name' => $customer->getLastname(),
            'email' => $customer->getEmail(),
            'requires_account' => self::CUSTOMER_REQUIRES_ACCOUNT,
            'external_id' => $customer->getId(),
        ];

        $openpayCustomer = $this->customerAdapter->store($params);

        return $openpayCustomer;
    }
}
