<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 10/12/15
 * Time: 10:52 AM
 */

namespace Degaray\Openpay\Model\Plugin\Card;

use Degaray\Openpay\Api\CardRepositoryInterface;
use Magento\Customer\Model\ResourceModel\CustomerRepository;

class SaveCustomerCards
{
    /**
     * @var CardRepositoryInterface
     */
    protected $cardRepository;

    /**
     * SaveCustomerCards constructor.
     * @param CardRepositoryInterface $cardRepository
     */
    public function __construct(
        CardRepositoryInterface $cardRepository
    ) {
        $this->cardRepository = $cardRepository;
    }

    public function aroundSave(CustomerRepository $subject, \Closure $proceed, $customer)
    {
        $partiallySavedCustomer = $proceed($customer);

        $customerToSave = $customer;
        $cardsToSave = $this->getCards($customerToSave);

        $newCards = $this->cardRepository->updateCards($cardsToSave, $partiallySavedCustomer);

        $savedCustomer = $partiallySavedCustomer->getExtensionAttributes()->setOpenpayCard($newCards);

        return $savedCustomer;
    }

    /**
     * @param $customer
     * @return null
     */
    protected function getCards($customer) {
        $existingCards = null;
        if ($customer->getId()) {
            $extensionAttributes = $customer->getExtensionAttributes();
            $existingCards = $extensionAttributes->getOpenpayCard();
        }

        return $existingCards;
    }
}