<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 10/12/15
 * Time: 10:52 AM
 */

namespace Degaray\Openpay\Model\Plugin\Customer;

use Degaray\Openpay\Api\CardRepositoryInterface;
use Degaray\Openpay\Api\Data\CardInterface;
use Degaray\Openpay\Model\Customer\OpenpayCustomerRepositoryInterface;
use Degaray\Openpay\Model\Exception\OpenpayException;
use Degaray\Openpay\Setup\UpgradeData;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\ResourceModel\Customer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

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

    /**
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerInterface $customer
     * @throws LocalizedException
     */
    public function beforeSave(CustomerRepositoryInterface $customerRepository, CustomerInterface $customer)
    {
        $this->validateOpenpayCustomerId($customerRepository, $customer);
        if ($this->shouldSaveOpenpayCustomerId($customerRepository, $customer)) {
            $openpayCustomer  = $this->saveCustomerInOpenpay($customer);
            $openpayCustomerId = $openpayCustomer->getId();
            $customer->setCustomAttribute(UpgradeData::OPENPAY_CUSTOMER_ID_CUSTOM_ATTRIBUTE, $openpayCustomerId);
        }

        $openpayCustomerId = $this->getOpenpayCustomerId($customer);
        $cards = [];
        $customerExtensionAtributes = $customer->getExtensionAttributes();
        if (!is_null($customerExtensionAtributes)) {
            $cards = $customerExtensionAtributes->getOpenpayCard();
            if (!isset($cards)) {
                throw new LocalizedException(__('You must provide an openpay_card with the extension_attributes.'));
            }
        }

        $currentCards = $this->cardRepository->getCardsByOpenpayCustomerId($openpayCustomerId);

        $cardsToDelete = $this->getCardsToDelete($currentCards, $cards);
        $this->deleteCards($cardsToDelete);

        $cardsToSave = $this->getCardsToSave($cards);
        $this->saveCards($openpayCustomerId, $cardsToSave);

        if ($this->shouldRefreshCards($cardsToSave, $cardsToDelete)) {
            $this->refreshCards($customer);
        }
    }

    /**
     * @param array $cardsToSave
     * @param array $cardsToDelete
     * @return bool
     */
    protected function shouldRefreshCards(array $cardsToSave, array $cardsToDelete)
    {
        if (count($cardsToSave) > 0 || count($cardsToDelete) > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param CustomerInterface $customer
     */
    protected function refreshCards(CustomerInterface $customer)
    {
        $openpayCustomerId = $this->getOpenpayCustomerId($customer);
        $cards = $this->cardRepository->getCardsByOpenpayCustomerId($openpayCustomerId);
        $customer->getExtensionAttributes()->setOpenpayCard($cards);
    }

    /**
     * @param $customerRepository
     * @param $customer
     * @return bool
     */
    protected function shouldSaveOpenpayCustomerId($customerRepository, $customer)
    {
        return is_null($this->getSavedOpenpayCustomerId($customerRepository, $customer));
    }

    /**
     * @param $customerRepository
     * @param $customer
     * @return string
     */
    protected function getSavedOpenpayCustomerId($customerRepository, $customer)
    {
        $savedOpenpayCustomerId = null;

        if (!is_null($customer->getId())) {
            $savedOpenpayCustomer = $customerRepository->getById($customer->getId());
            $savedOpenpayCustomerId = $this->getOpenpayCustomerId($savedOpenpayCustomer);
        }

        return $savedOpenpayCustomerId;
    }

    /**
     * @param $customerRepository
     * @param $customer
     * @throws LocalizedException
     */
    public function validateOpenpayCustomerId($customerRepository, $customer)
    {
        $savedOpenpayCustomerId = $this->getSavedOpenpayCustomerId($customerRepository, $customer);
        $openpayCustomerId = $this->getOpenpayCustomerId($customer);

        // give no errors in case saved openapay customerId does not exist
        if (is_null($savedOpenpayCustomerId)) {
            return;
        }

        // give error in case request wants to update the openpay customerId
        if ($openpayCustomerId !== $savedOpenpayCustomerId) {
            throw new LocalizedException(__('Openpay Customer Id cannot be updated'));
        }
    }

    /**
     * @param CustomerInterface $customerDataModel
     * @return string
     */
    protected function getOpenpayCustomerId(CustomerInterface $customerDataModel)
    {
        $openpayCustomer = $customerDataModel->getCustomAttribute(
            UpgradeData::OPENPAY_CUSTOMER_ID_CUSTOM_ATTRIBUTE
        );

        $openpayCustomerId = (is_null($openpayCustomer))? null : $openpayCustomer->getValue();

        return $openpayCustomerId;
    }

    /**
     * @param CardInterface[] $cardsToDelete
     * @return bool
     */
    protected function deleteCards(array $cardsToDelete)
    {
        foreach ($cardsToDelete as $card) {
            $this->cardRepository->delete($card);
        }

        return true;
    }

    /**
     * @param string $openpayCustomerId
     * @param CardInterface[] $cardsToSave
     * @return array
     */
    protected function saveCards($openpayCustomerId, array $cardsToSave)
    {
        $savedCards = [];
        foreach ($cardsToSave as $card) {
            $savedCards[] = $this->cardRepository->save($openpayCustomerId, $card);
        }

        return $savedCards;
    }

    /**
     * @param array $cards
     * @return array
     */
    protected function getCardsToSave(array $cards)
    {
        $cardsToSave = [];

        foreach ($cards as $card) {
            if ($this->cardHasToken($card)) {
                $cardsToSave[] = $card;
            }
        }

        return $cardsToSave;
    }

    /**
     * @param array $card
     * @return bool
     */
    protected function cardHasToken($card)
    {
        if (is_string($card['token'])) {
            return true;
        }

        return false;
    }

    /**
     * @param CardInterface[] $currentCards
     * @param CardInterface[] $cardsToSave
     * @return CardInterface[]
     */
    protected function getCardsToDelete(array $currentCards, array $cardsToSave)
    {
        $cardsToDelete = [];
        foreach ($currentCards as $currentCard) {
            if ($this->cardShouldBeDeleted($currentCard, $cardsToSave)) {
                $cardsToDelete[] = $currentCard;
            }
        }

        return $cardsToDelete;
    }

    /**
     * @param CardInterface $card
     * @param CardInterface[] $cardsToSave
     * @return bool
     */
    protected function cardShouldBeDeleted(CardInterface $card, array $cardsToSave)
    {
        foreach ($cardsToSave as $cardToSave) {

            if (!isset($cardToSave['card_id'])) {
                $cardToSave['card_id'] = null;
            }

            if ($card->getCardId() == $cardToSave['card_id']) {
                return false;
            }
        }

        return true;
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
}
