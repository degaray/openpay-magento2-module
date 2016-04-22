<?php

namespace Degaray\Openpay\Helper;

use Magento\Customer\Api\Data\CustomerInterface;
use Degaray\Openpay\Setup\UpgradeData;


/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 4/02/16
 * Time: 02:05 PM
 *
 * Class CustomerHelper
 * @package Degaray\Openpay\Helper
 */
class CustomerHelper
{
    /**
     * @param CustomerInterface $customerDataObject
     * @return string
     */
    public function getOpenpayCustomerId(CustomerInterface $customerDataObject)
    {
        $openpayCustomer = $customerDataObject->getCustomAttribute(UpgradeData::OPENPAY_CUSTOMER_ID_CUSTOM_ATTRIBUTE);

        $openpayCustomerId = ($openpayCustomer)?
        $openpayCustomer->getValue(UpgradeData::OPENPAY_CUSTOMER_ID_CUSTOM_ATTRIBUTE) : null;

        return $openpayCustomerId;
    }
}