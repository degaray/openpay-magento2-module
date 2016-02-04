<?php

namespace Degaray\Openpay\Model\Mapper;


use Degaray\Openpay\Api\Data\StoreInterface;
use Openpay\Client\Type\OpenpayStoreType;

/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 4/02/16
 * Time: 01:36 PM
 *
 * Class StoreMapper
 * @package Degaray\Openpay\Model\Mapper
 */
class StoreMapper
{
    /**
     * @var StoreInterface
     */
    protected $object;

    /**
     * StoreMapper constructor.
     * @param StoreInterface $storeType
     */
    public function __construct(StoreInterface $storeType)
    {
        $this->object = $storeType;
    }

    /**
     * @param OpenpayStoreType $storeType
     * @return StoreInterface
     */
    public function create(OpenpayStoreType $storeType)
    {
        return $this->populate($storeType);
    }

    /**
     * @param OpenpayStoreType $storeType
     * @return StoreInterface
     */
    protected function populate(OpenpayStoreType $storeType)
    {
        $object = clone $this->object;
        $object->setBarcodeUrl($storeType->getBarcodeUrl());
        $object->setReference($storeType->getReference());

        return $object;
    }
}
