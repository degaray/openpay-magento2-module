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

class CardRepository implements CardRepositoryInterface
{
    private $cardManager;

    public function __construct(

    )
    {
    }

    public function save(CardInterface $cardInterface)
    {
        // TODO: Implement save() method.
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