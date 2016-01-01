<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 30/12/15
 * Time: 02:00 PM
 */

namespace Degaray\Openpay\Model\Card;

use Degaray\Openpay\Api\CardRepositoryInterface;
use Degaray\Openpay\Api\Data\CardInterface;
use Degaray\Openpay\Model\Mapper\CardMapper;
use Openpay\Client\Adapter\OpenpayCardAdapterInterface;
use Openpay\Client\Adapter\OpenpayCustomerAdapterInterface;

class OpenpayCardRepository implements CardRepositoryInterface
{
    /**
     * @var OpenpayCustomerAdapterInterface
     */
    protected $customerAdapter;

    /**
     * @var OpenpayCardAdapterInterface
     */
    protected $cardAdapter;

    /**
     * @var CardMapper
     */
    protected $cardMapper;

    /**
     * OpenpayCardRepository constructor.
     * @param OpenpayCustomerAdapterInterface $openpayCustomerAdapter
     * @param OpenpayCardAdapterInterface $openpayCardAdapter
     * @param CardMapper $cardMapper
     */
    public function __construct(
        OpenpayCustomerAdapterInterface $openpayCustomerAdapter,
        OpenpayCardAdapterInterface $openpayCardAdapter,
        CardMapper $cardMapper
    ) {
        $this->cardAdapter = $openpayCardAdapter;
        $this->customerAdapter = $openpayCustomerAdapter;
        $this->cardMapper = $cardMapper;
    }

    /**
     * @param CardInterface $card
     * @return \Openpay\Client\Type\OpenpayCardType
     */
    public function save(CardInterface $card)
    {
        $params = [
            'token_id' => $card->getToken(),
            'device_session_id' => $card->getDeviceSessionId()
        ];
        $openpayCard = $this->cardAdapter->store($card->getCustomerId(), $params);

        return $openpayCard;
    }

    /**
     * @param string $openpayCustomerId
     * @return CardInterface[]
     */
    public function getCardsByOpenpayCustomerId($openpayCustomerId)
    {
        $cardsArray = $this->cardAdapter->getList($openpayCustomerId);

        $openpayCards = [];
        foreach ($cardsArray as $card) {
            $openpayCards[] = $this->cardMapper->create($card);
        }

        return $openpayCards;
    }

    public function getById($cardId)
    {
        // TODO: Implement getById() method.
    }

    public function delete(CardInterface $cardInterface)
    {
        // TODO: Implement delete() method.
    }

    public function deleteById($cardId)
    {
        // TODO: Implement deleteById() method.
    }


}