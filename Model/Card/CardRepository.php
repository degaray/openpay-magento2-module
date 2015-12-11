<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 4/12/15
 * Time: 04:42 PM
 */

namespace Degaray\Openpay\Model\Card;

use Degaray\Openpay\Api\CardRepositoryInterface;
use Degaray\Openpay\Api\Data\CardInterface;
use Degaray\Openpay\Model\Card;
use Magento\Customer\Model\CustomerRegistry;
use Degaray\Openpay\Model\CardFactory;
use Magento\Customer\Model\Data\Customer;
use Magento\Framework\Webapi\Exception;
use Psr\Log\LoggerInterface;

class CardRepository implements CardRepositoryInterface
{
    /**
     * @var \Magento\Customer\Model\CustomerRegistry
     */
    protected $customerRegistry;

    /**
     * @var CardRegistry
     */
    protected $cardRegistry;

    /**
     * @var CardFactory
     */
    protected $cardFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * CardRepository constructor.
     * @param CustomerRegistry $customerRegistry
     * @param CardRegistry $cardRegistry
     * @param CardFactory $cardFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        CustomerRegistry $customerRegistry,
        CardRegistry $cardRegistry,
        CardFactory $cardFactory,
        LoggerInterface $logger
    ) {
        $this->customerRegistry = $customerRegistry;
        $this->cardRegistry = $cardRegistry;
        $this->cardFactory = $cardFactory;
        $this->logger = $logger;
    }


    public function save(CardInterface $card)
    {
        $cardModel = null;
        $cardId = $card->getId();
        $cardModel = $this->getById($cardId);

        if ($cardModel === null) {
            die('TODO: SAVE NEW CARDS!!!');
        }

        $cardModel->setOpenpayCardId($card->getOpenpayCardId());

        $cardModel->save();

        $savedCard = $this->getById($cardId);
        return $savedCard;
    }

    /**
     * @param Card[] $cardList
     * @param Customer $customer
     */
    public function updateCards(array $cardList, Customer $customer)
    {
        $customerCards = $this->getCustomerCards($customer);

        $cardIds = $this->getCardIds($cardList);
        $customerCardIds = $this->getCardIds($customerCards);

        // check cards to update exist
        $unexistentCards = array_diff($cardIds, $customerCardIds);
        if (count($unexistentCards) > 0) {
            $unexistentCardsString = implode(', ', $unexistentCards);
            $errorMessage = 'Cannot save cards. Card Id' .
                ((count($unexistentCards)>1)?'s':'') .
                ' ' .
                $unexistentCardsString .
                ' do'.
                ((count($unexistentCards)==1)?'es':'') .
                ' not exist.';
            $this->logger->error($errorMessage);

            throw new \Exception($errorMessage);
        }

        // no existent cards will be updated for now

        // delete cards that were not sent in the request
        $cardsIdsToDelete = array_diff($customerCardIds, $cardIds);
        $this->deleteCards($cardsIdsToDelete);

        // create new cards
        $cardsWithNoIds = $this->getCardsWithNoId($cardList);

        // create openpay cards

    }

    public function saveCards($cardsToSave, $customerId)
    {
        $savedCards = [];
        foreach ($cardsToSave as $index => $card) {
            $card->setCustomerId($customerId);
            $savedCard = $this->save($card);
            $savedCards[$index] = $savedCard;
        }

        return $savedCards;
    }

    public function getById($cardId)
    {
        $cardModel = $this->cardRegistry->retrieve($cardId);
        return $cardModel;
    }

    public function delete(CardInterface $card)
    {
        $card = $this->cardRegistry->retrieve($card->getId());

        return $card->delete();
    }

    public function deleteById($cardId)
    {
        $card = $this->cardRegistry->retrieve($cardId);

        return $card->delete();
    }

    /**
     * @param Card[] $cards
     * @return array
     */
    protected function getCardIds(array $cards)
    {
        $cardIds = [];

        foreach ($cards as $card) {
            $cardId = $card->getId();
            if ($cardId !== null) {
                $cardIds[] = $cardId;
            }
        }

        return $cardIds;
    }

    /**
     * @param Customer $customer
     * @return Card[]
     */
    protected function getCustomerCards(Customer $customer)
    {
        $customerCards = $customer->getExtensionAttributes()->getOpenpayCard();

        return $customerCards;
    }

    /**
     * @param array $cardIds
     * @return bool
     */
    public function deleteCards(array $cardIds)
    {
        foreach ($cardIds as $cardId) {
            $this->deleteById($cardId);
        }

        return true;
    }

    /**
     * @param Card[] $cards
     * @return Card[]
     */
    protected function getCardsWithNoId(array $cards)
    {
        $cardsWithNoIds = [];

        foreach ($cards as $card) {
            if ($card->getId() === null) {
                $cardsWithNoIds[] = $card;
            }
        }

        return $cardsWithNoIds;
    }
}