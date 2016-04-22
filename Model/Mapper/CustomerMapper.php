<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 4/02/16
 * Time: 01:36 PM
 */

namespace Degaray\Openpay\Model\Mapper;


use Degaray\Openpay\Api\Data\CustomerInterface;
use Openpay\Client\Type\OpenpayCustomerType;

class CustomerMapper
{
    /**
     * @var CustomerInterface
     */
    protected $object;

    /**
     * @var AddressMapper
     */
    protected $addressMapper;

    /**
     * @var StoreMapper
     */
    protected $storeMapper;

    /**
     * CustomerMapper constructor.
     * @param CustomerInterface $customerType
     * @param AddressMapper $addressMapper
     * @param StoreMapper $storeMapper
     */
    public function __construct(
        CustomerInterface $customerType,
        AddressMapper $addressMapper,
        StoreMapper $storeMapper
    )
    {
        $this->object = $customerType;
        $this->addressMapper = $addressMapper;
        $this->storeMapper = $storeMapper;
    }

    public function create(OpenpayCustomerType $customerType)
    {
        return $this->populate($customerType);
    }

    /**
     * @param OpenpayCustomerType $customerType
     * @return CustomerInterface
     */
    protected function populate(OpenpayCustomerType $customerType)
    {
        $object = clone $this->object;
        $addressMapper = $this->addressMapper->create($customerType->getAddress());
        $object->setEntityId($customerType->getId());
        $object->setAddress($addressMapper);
        $object->setBalance($customerType->getBalance());
        $object->setClabe($customerType->getClabe());
        $object->setCreationDate($customerType->getCreationDate());
        $object->setEmail($customerType->getEmail());
        $object->setName($customerType->getName());
        $object->setLastName($customerType->getLastName());
        $object->setPhoneNumber($customerType->getPhoneNumber());
        $storeType = $this->storeMapper->create($customerType->getStore());
        $object->setStore($storeType);

        return $object;
    }
}