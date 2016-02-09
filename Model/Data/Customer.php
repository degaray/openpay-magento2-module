<?php

namespace Degaray\Openpay\Model\Data;

use Degaray\Openpay\Api\Data\AddressInterface;
use Degaray\Openpay\Api\Data\CustomerInterface;
use Degaray\Openpay\Api\Data\StoreInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 4/02/16
 * Time: 11:37 AM
 *
 * Class Customer
 * @package Degaray\Openpay\Model\Data
 */
class Customer extends AbstractModel implements CustomerInterface
{
    /**
     * @var string
     */
    protected $entity_id;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $creation_date;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $last_name;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $phone_number;

    /**
     * @var string
     */
    protected $external_id;

    /**
     * @var string
     */
    protected $balance;

    /**
     * @var AddressInterface
     */
    protected $address;

    /**
     * @var StoreInterface
     */
    protected $store;

    /**
     * @var string
     */
    protected $clabe;

    /**
     * @return string
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @param string $entity_id
     * @return $this
     */
    public function setEntityId($entity_id)
    {
        $this->setData(self::ENTITY_ID, $entity_id);
        return $this;
    }

    /**
     * @return \Magento\Framework\Stdlib\DateTime
     */
    public function getCreationDate()
    {
        return $this->getData(self::CREATION_DATE);
    }

    /**
     * @param \Magento\Framework\Stdlib\DateTime $creation_date
     * @return $this
     */
    public function setCreationDate($creation_date)
    {
        $this->setData(self::CREATION_DATE, $creation_date);
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->setData(self::NAME, $name);
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->getData(self::LAST_NAME);
    }

    /**
     * @param string $last_name
     * @return $this
     */
    public function setLastName($last_name)
    {
        $this->setData(self::LAST_NAME, $last_name);
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->setData(self::EMAIL, $email);
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->getData(self::PHONE_NUMBER);
    }

    /**
     * @param string $phone_number
     * @return $this
     */
    public function setPhoneNumber($phone_number)
    {
        $this->setData(self::PHONE_NUMBER, $phone_number);
        return $this;
    }

    /**
     * @return string
     */
    public function getExternalId()
    {
        return $this->getData(self::EXTERNAL_ID);
    }

    /**
     * @param string $external_id
     * @return $this
     */
    public function setExternalId($external_id)
    {
        $this->setData(self::EXTERNAL_ID, $external_id);
        return $this;
    }

    /**
     * @return string
     */
    public function getBalance()
    {
        return $this->getData(self::BALANCE);
    }

    /**
     * @param $balance
     * @return $this
     */
    public function setBalance($balance)
    {
        $this->setData(self::BALANCE, $balance);
        return $this;
    }

    /**
     * @return AddressInterface
     */
    public function getAddress()
    {
        return $this->getData(self::ADDRESS);
    }

    /**
     * @param AddressInterface $address
     * @return $this
     */
    public function setAddress(AddressInterface $address)
    {
        $this->setData(self::ADDRESS, $address);
        return $this;
    }

    /**
     * @return StoreInterface
     */
    public function getStore()
    {
        return $this->getData(self::STORE);
    }

    /**
     * @param \Degaray\Openpay\Api\Data\StoreInterface $store
     * @return $this
     */
    public function setStore(StoreInterface $store)
    {
        $this->setData(self::STORE, $store);
        return $this;
    }

    /**
     * @return string
     */
    public function getClabe()
    {
        return $this->getData(self::CLABE);
    }

    /**
     * @param string $clabe
     * @return $this
     */
    public function setClabe($clabe)
    {
        $this->setData(self::CLABE, $clabe);
        return $this;
    }
}
