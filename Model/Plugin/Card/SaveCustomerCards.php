<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 10/12/15
 * Time: 10:52 AM
 */

namespace Degaray\Openpay\Model\Plugin\Card;

use Degaray\Openpay\Api\CardRepositoryInterface;
use Degaray\Openpay\Model\Customer\OpenpayCustomerRepositoryInterface;
use Degaray\Openpay\Setup\UpgradeData;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\ResourceModel\CustomerRepository;

class SaveCustomerCards
{
    /**
     * @var CardRepositoryInterface
     */
    protected $cardRepository;

    /**
     * @var OpenpayCustomerRepositoryInterface
     */
    protected $openpayCustomerRepository;

    /**
     * SaveCustomerCards constructor.
     * @param CardRepositoryInterface $cardRepository
     * @param OpenpayCustomerRepositoryInterface $openpayCustomerRepository
     */
    public function __construct(
        CardRepositoryInterface $cardRepository,
        OpenpayCustomerRepositoryInterface $openpayCustomerRepository
    ) {
        $this->cardRepository = $cardRepository;
        $this->openpayCustomerRepository = $openpayCustomerRepository;
    }

    public function aroundSave(CustomerRepository $subject, \Closure $proceed, $customer)
    {
        $cardsToSave = $this->getCards($customer);

        $openpayCustomerId = $customer->getCustomAttribute(
            UpgradeData::OPENPAY_CUSTOMER_ID_CUSTOM_ATTRIBUTE
        );
        if ($this->needsOpenpayCustomer($openpayCustomerId, $cardsToSave)) {
            $previouslySavedCustomer = $subject->get($customer->getEmail());
            $customer = (is_null($previouslySavedCustomer))? $customer : $previouslySavedCustomer;
            $openpayCustomer = $this->saveCustomerInOpenpay($customer);
            $openpayCustomerId = $openpayCustomer->getId();
            $customer = $this->setCutomerOpenpayCustomerId($openpayCustomerId, $customer);
            $customer = $proceed($customer);
        }

        $savedCards = [];
        foreach ($cardsToSave as $card) {
            $card->setCustomerId($openpayCustomerId);
            $savedCards[] = $this->cardRepository->save($card);
        }

        return $customer;
    }

    /**
     * @param $customer
     * @return \Openpay\Client\Type\OpenpayCustomerType
     */
    protected function saveCustomerInOpenpay($customer)
    {
        $openpayCustomer = $this->openpayCustomerRepository->save($customer);

        return $openpayCustomer;
    }

    /**
     * @param $openpayCustomerId
     * @param $customer
     * @return mixed
     */
    protected function setCutomerOpenpayCustomerId($openpayCustomerId, $customer)
    {
        $customer->setCustomAttribute(
            UpgradeData::OPENPAY_CUSTOMER_ID_CUSTOM_ATTRIBUTE,
            $openpayCustomerId
        );

        return $customer;
    }

    /**
     * @param string $openpayCustomerId
     * @param string $cardsToSave
     * @return bool
     */
    protected function needsOpenpayCustomer($openpayCustomerId, $cardsToSave)
    {
        // openpay customer is set
        if (!is_null($openpayCustomerId)) {
            return false;
        }
        // there are cards to save
        if (count($cardsToSave) > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param $customer
     * @return \Degaray\Openpay\Api\Data\CardInterface[]
     */
    protected function getCards($customer) {
        $existingCards = null;
        if ($customer->getEmail()) {
            $extensionAttributes = $customer->getExtensionAttributes();
            $existingCards = $extensionAttributes->getOpenpayCard();
        }

        return $existingCards;
    }
}