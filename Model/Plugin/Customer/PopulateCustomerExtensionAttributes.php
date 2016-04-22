<?php

namespace Degaray\Openpay\Model\Plugin\Customer;

use Degaray\Openpay\Api\CardRepositoryInterface;
use Degaray\Openpay\Api\Data\CardInterface;
use Degaray\Openpay\Helper\CustomerHelper;
use Degaray\Openpay\Model\Customer\OpenpayCustomerRepositoryInterface;
use Degaray\Openpay\Model\ResourceModel\Card\CollectionFactory as CardCollectionFactory;
use Magento\Customer\Api\Data\CustomerExtensionFactory;
use Magento\Customer\Model\Customer;

/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 3/12/15
 * Time: 12:36 PM
 *
 * Class PopulateCustomerExtensionAttributes
 * @package Degaray\Openpay\Model\Plugin\Card
 */
class PopulateCustomerExtensionAttributes
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
     * @var CustomerHelper
     */
    protected $customerHelper;

    /**
     * @var OpenpayCustomerRepositoryInterface
     */
    protected $openpayCustomerRepository;

    /**
     * PopulateCustomerCards constructor.
     * @param CustomerExtensionFactory $customerExtensionFactory
     * @param CardCollectionFactory $cardCollectionFactory
     * @param CardRepositoryInterface $cardRepository
     * @param OpenpayCustomerRepositoryInterface $openpayCustomerRepository
     * @param CustomerHelper $customerHelper
     */
    public function __construct(
        CustomerExtensionFactory $customerExtensionFactory,
        CardCollectionFactory $cardCollectionFactory,
        CardRepositoryInterface $cardRepository,
        OpenpayCustomerRepositoryInterface $openpayCustomerRepository,
        CustomerHelper $customerHelper
    ) {
        $this->customerExtensionFactory = $customerExtensionFactory;
        $this->cardCollectionFactory = $cardCollectionFactory;
        $this->cardRepository = $cardRepository;
        $this->customerHelper = $customerHelper;
        $this->openpayCustomerRepository = $openpayCustomerRepository;
    }

    /**
     * @param Customer $customer
     * @param $customerDataObject
     * @return \Magento\Customer\Model\Data\Customer
     */
    public function afterGetDataModel(Customer $customer, $customerDataObject)
    {
        $openpayCustomerId = $this->customerHelper->getOpenpayCustomerId($customerDataObject);

        if (!is_null($openpayCustomerId)) {
            $openpayCards = $this->getCardsFromOpenpay($openpayCustomerId);
            $openpayCustomer = $this->getCustomerFromOpenpay($openpayCustomerId);

            $extensionAttributes = $this->customerExtensionFactory->create()
                ->setOpenpayCard($openpayCards)
                ->setOpenpayCustomer($openpayCustomer);
            $customerDataObject->setExtensionAttributes($extensionAttributes);
        }

        return $customerDataObject;
    }

    /**
     * @param $customerDataObject
     * @return CardInterface[]
     */
    protected function getCardsFromOpenpay($customerDataObject)
    {
        try {
            $cards = $this->cardRepository->getCardsByOpenpayCustomerId($customerDataObject);
        } catch (\Exception $e) {
            $cards = [
                'error' => __('Could not retrieve available cards from OpenPay for the given user')
            ];
        }
        return $cards;
    }

    /**
     * @param $customerDataObject
     * @return array|\Openpay\Client\Type\OpenpayCustomerType
     */
    protected function getCustomerFromOpenpay($customerDataObject)
    {
        try {
            $customer = $this->openpayCustomerRepository->get($customerDataObject);
        } catch (\Exception $e) {
            $customer = [
                'error' => __('Could not retrieve available customer from OpenPay for the given user')
            ];
        }
        return $customer;
    }
}
