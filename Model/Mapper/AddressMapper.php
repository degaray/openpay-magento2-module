<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 31/12/15
 * Time: 03:20 PM
 */

namespace Degaray\Openpay\Model\Mapper;


use Magento\Customer\Api\Data\AddressInterface;
use Openpay\Client\Type\OpenpayAddressType;
use Magento\Customer\Api\Data\RegionInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;

class AddressMapper
{
    const COUNTRY_CODE_MEXICO = 'MX';
    /**
     * @var AddressInterface
     */
    protected $object;

    /**
     * @var RegionInterface
     */
    protected $region;

    /**
     * AddressMapper constructor.
     * @param AddressInterfaceFactory $factory
     * @param RegionInterface $region
     */
    public function __construct(AddressInterfaceFactory $factory, RegionInterface $region)
    {
        $this->object = $factory->create([]);
        $this->region = $region;
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
        $this->object->setCity($addressType->getCity());
        $this->object->setCountryId(self::COUNTRY_CODE_MEXICO);
        $this->object->setPostcode($addressType->getPostalCode());
        $region = $this->region->setRegion($addressType->getState());
        $this->object->setRegion($region);
        $street = [
            $addressType->getLine1(),
            $addressType->getLine2(),
            $addressType->getLine3()
        ];
        $this->object->setStreet($street);

        return $this->object;
    }
}