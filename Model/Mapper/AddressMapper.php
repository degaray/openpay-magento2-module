<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 31/12/15
 * Time: 03:20 PM
 */

namespace Degaray\Openpay\Model\Mapper;

use Degaray\Openpay\Api\Data\AddressInterface;
use Openpay\Client\Type\OpenpayAddressType;

class AddressMapper
{
    const COUNTRY_CODE_MEXICO = 'MX';

    /**
     * @var OpenpayAddressType
     */
    protected $object;

    /**
     * AddressMapper constructor.
     * @param AddressInterface $addressType
     */
    public function __construct(AddressInterface $addressType)
    {
        $this->object = $addressType;
    }

    /**
     * @param OpenpayAddressType $addressType
     * @return AddressInterface
     */
    public function create(OpenpayAddressType $addressType)
    {
        return $this->populate($addressType);
    }

    /**
     * @param OpenpayAddressType $addressType
     * @return AddressInterface
     */
    protected function populate(OpenpayAddressType $addressType)
    {
        $object = clone $this->object;
        $object->setCity($addressType->getCity());
        $object->setState($addressType->getState());
        $object->setCountryCode($addressType->getCountryCode());
        $object->setLine1($addressType->getLine1());
        $object->setLine2($addressType->getLine2());
        $object->setLine3($addressType->getLine3());
        $object->setPostalCode($addressType->getPostalCode());

        return $object;
    }
}
