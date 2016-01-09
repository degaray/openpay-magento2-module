<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 3/12/15
 * Time: 12:36 PM
 */

namespace Degaray\Openpay\Model\Plugin\Card;

use Degaray\Openpay\Api\CardRepositoryInterface;
use Degaray\Openpay\Api\Data\CardInterface;
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
     * @return \Magento\Customer\Model\Data\Customer
     */
    public function afterGetDataModel(Customer $customer, $customerDataObject)
    {
        $openpayCustomerId = $this->getOpenpayCustomerId($customerDataObject);
        $cards = [];

        if (!is_null($openpayCustomerId)) {
            $cards = $this->getCardsFromOpenpay($openpayCustomerId);
        }

        $extension = $this->customerExtensionFactory->create()->setOpenpayCard($cards);
        $customerDataObject->setExtensionAttributes($extension);

        return $customerDataObject;
    }

    /**
     * @param $customerDataObject
     * @return CardInterface
     */
    protected function getCardsFromOpenpay($customerDataObject)
    {
        try {
            $cards = $this->cardRepository->getCardsByOpenpayCustomerId($customerDataObject);
        } catch (\Exception $e) {
            $cards = [
                'error' => __('Could not retrieve available cards from openpay for the given user')
            ];
        }
        return $cards;
    }

    /**
     * @param $customerDataObject
     * @return string
     */
    protected function getOpenpayCustomerId($customerDataObject)
    {
        $openpayCustomer = $customerDataObject->getCustomAttribute(UpgradeData::OPENPAY_CUSTOMER_ID_CUSTOM_ATTRIBUTE);

        $openpayCustomerId = ($openpayCustomer)?
            $openpayCustomer->getValue(UpgradeData::OPENPAY_CUSTOMER_ID_CUSTOM_ATTRIBUTE) : null;

        return $openpayCustomerId;
    }
}
