<?php

namespace Degaray\Openpay\Model\Mapper;

use Degaray\Openpay\Api\Data\CardInterface;
use Openpay\Client\Type\OpenpayCardType;

/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 31/12/15
 * Time: 01:08 PM
 */
class CardMapper
{
    /**
     * @var CardInterface
     */
    protected $object;

    /**
     * @var AddressMapper
     */
    protected $addressMapper;

    /**
     * CardMapper constructor.
     * @param CardInterface $object
     * @param AddressMapper $addressMapper
     */
    public function __construct(CardInterface $object, AddressMapper $addressMapper)
    {
        $this->object = $object;
        $this->addressMapper = $addressMapper;
    }

    /**
     * @param OpenpayCardType $cardType
     * @return CardInterface
     */
    public function create(OpenpayCardType $cardType)
    {
        return $this->populate($cardType);
    }

    /**
     * @param OpenpayCardType $cardType
     * @return CardInterface
     */
    protected function populate(OpenpayCardType $cardType)
    {
        $object = clone $this->object;
        $object->setCustomerId($cardType->getCustomerId());
        $object->setCardId($cardType->getId());
        $object->setCreatedAt($cardType->getCreationDate());

        $object->setType($cardType->getType());
        $object->setBrand($cardType->getBrand());
        $object->setCardNumber($cardType->getCardNumber());
        $object->setHolderName($cardType->getHolderName());
        $object->setExpirationYear($cardType->getExpirationYear());
        $object->setExpirationMonth($cardType->getExpirationMonth());
        $object->setAllowsCharges($cardType->isAllowsCharges());
        $object->setAllowsPayouts($cardType->isAllowsPayouts());
        $object->setBankName($cardType->getBankName());
        $object->setBankCode($cardType->getBankCode());

        if (!is_null($cardType->getAddress())) {
            $address = $this->addressMapper->create($cardType->getAddress());
            $object->setAddress($address);
        }

        return $object;
    }
}
