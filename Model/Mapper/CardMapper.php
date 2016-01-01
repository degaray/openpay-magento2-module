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
        $this->object->setCustomerId($cardType->getCustomerId());
        $this->object->setCardId($cardType->getId());
        $this->object->setCreatedAt($cardType->getCreationDate());

        $this->object->setType($cardType->getType());
        $this->object->setBrand($cardType->getBrand());
        $this->object->setCardNumber($cardType->getCardNumber());
        $this->object->setHolderName($cardType->getHolderName());
        $this->object->setExpirationYear($cardType->getExpirationYear());
        $this->object->setExpirationMonth($cardType->getExpirationMonth());
        $this->object->setAllowsCharges($cardType->isAllowsCharges());
        $this->object->setAllowsPayouts($cardType->isAllowsPayouts());
        $this->object->setBankName($cardType->getBankName());
        $this->object->setBankCode($cardType->getBankCode());

        $address = $this->addressMapper->create($cardType->getAddress());
        $this->object->setAddress($address);

        return $this->object;
    }
}
