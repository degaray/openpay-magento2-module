<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 3/12/15
 * Time: 12:36 PM
 */

namespace Degaray\Openpay\Model\Plugin\Card;

use Degaray\Openpay\Api\CardRepositoryInterface;
use Degaray\Openpay\Model\ResourceModel\Card\CollectionFactory as CardCollectionFactory;
use Degaray\Openpay\Setup\UpgradeData;
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
     * @var CardRepositoryInterface
     */
    protected $cardRepository;

    /**
     * PopulateCustomerCards constructor.
     * @param CustomerExtensionFactory $customerExtensionFactory
     * @param CardCollectionFactory $cardCollectionFactory
     * @param CardRepositoryInterface $cardRepository
     */
    public function __construct(
        CustomerExtensionFactory $customerExtensionFactory,
        CardCollectionFactory $cardCollectionFactory,
        CardRepositoryInterface $cardRepository
    ) {
        $this->customerExtensionFactory = $customerExtensionFactory;
        $this->cardCollectionFactory = $cardCollectionFactory;
        $this->cardRepository = $cardRepository;
    }

    /**
     * @param Customer $customer
     * @param $customerDataObject
     * @return mixed
     */
    public function afterGetDataModel(Customer $customer, $customerDataObject)
    {
        $openpayCustomer = $customerDataObject->getCustomAttribute(UpgradeData::OPENPAY_CUSTOMER_ID_CUSTOM_ATTRIBUTE);
        $cards = [];
        if (!is_null($openpayCustomer)) {
            $openpayCustomerId = $openpayCustomer->getValue('openpay_customer_id');
            $cards = $this->cardRepository->getCardsByOpenpayCustomerId($openpayCustomerId);
        }

        $extension = $this->customerExtensionFactory->create()->setOpenpayCard($cards);
        $customerDataObject->setExtensionAttributes($extension);

        return $customerDataObject;
    }
}
