<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 3/12/15
 * Time: 12:36 PM
 */

namespace Degaray\Openpay\Model\Plugin\Card;

use Degaray\Openpay\Model\ResourceModel\Card\CollectionFactory as CardCollectionFactory;
use Magento\Customer\Api\Data\CustomerExtensionFactory;
use Magento\Customer\Model\Customer;

/**
 * Class PopulateCustomerCards
 * @package Degaray\Openpay\Model\Plugin\Card
 */
class PopulateCustomerCards
{
    const CUSTOMER_EXTENSION_FACTORY_PATH = 'Magento\Customer\Api\Data\CustomerExtensionFactory';
    /**
     * @var \Magento\Customer\Api\Data\CustomerExtensionFactory
     */
    protected $customerExtensionFactory;

    /**
     * @var CardCollectionFactory
     */
    protected $cardCollectionFactory;

    /**
     * PopulateCustomerCards constructor.
     * @param CustomerExtensionFactory $customerExtensionFactory
     * @param CardCollectionFactory $cardCollectionFactory
     */
    public function __construct(
        CustomerExtensionFactory $customerExtensionFactory,
        CardCollectionFactory $cardCollectionFactory
    ) {
        $this->customerExtensionFactory = $customerExtensionFactory;
        $this->cardCollectionFactory = $cardCollectionFactory;
    }

    /**
     * @param Customer $customer
     * @param $customerDataObject
     * @return mixed
     */
    public function afterGetDataModel(Customer $customer, $customerDataObject)
    {
        $cardCollection = $this->cardCollectionFactory->create();
        $customerId = $customer->getId();
        $customerCards = $cardCollection->getCustomerCardsByCustomerId($customerId);
        $extension = $this->customerExtensionFactory->create()->setOpenpayCard($customerCards);
        $customerDataObject->setExtensionAttributes($extension);

        return $customerDataObject;
    }
}
