<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 10/12/15
 * Time: 01:34 PM
 */

namespace Degaray\Openpay\Model\Card;

use Degaray\Openpay\Model\CardFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class CardRegistry
{
    /**
     * @var CardFactory
     */
    protected $cardFactory;

    /**
     * @var array
     */
    private $cardRegistryById = [];

    /**
     * CardRegistry constructor.
     * @param CardFactory $cardFactory
     */
    public function __construct(
        CardFactory $cardFactory
    ) {
        $this->cardFactory = $cardFactory;
    }

    /**
     * @param $cardId
     * @return \Degaray\Openpay\Model\Data\Card
     * @throws NoSuchEntityException
     */
    public function retrieve($cardId)
    {
        if (isset($this->cardRegistryById[$cardId])) {
            return $this->cardRegistryById[$cardId];
        }

        $card = $this->cardFactory->create();
            $card->load($cardId);

        if (!$card->getId()) {
            // card does not exist
            throw NoSuchEntityException::singleField('cardId', $cardId);
        }

        $this->cardRegistryById[$cardId] = $card;
        return $card;
    }
}
