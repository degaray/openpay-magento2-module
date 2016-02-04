<?php
namespace Degaray\Openpay\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 4/02/16
 * Time: 11:08 AM
 *
 * Interface CustomerInterface
 * @package Degaray\Openpay\Api\Data
 */
interface CustomerInterface extends ExtensibleDataInterface
{
    const ENTITY_ID = 'entity_id';
    const CREATION_DATE = 'creation_date';
    const NAME = 'name';
    const LAST_NAME = 'last_name';
    const EMAIL = 'email';
    const PHONE_NUMBER = 'phone_number';
    const EXTERNAL_ID = 'external_id';
    const BALANCE = 'balance';
    const ADDRESS = 'address';
    const STORE = 'balance';
    const CLABE = 'clabe';

    /**
     * @api
     * @return string
     */
    public function getEntityId();

    /**
     * @api
     * @param string $entity_id
     * @return $this
     */
    public function setEntityId($entity_id);

    /**
     * @api
     * @return \DateTime
     */
    public function getCreationDate();

    /**
     * @api
     * @param \DateTime $creation_date
     * @return $this
     */
    public function setCreationDate($creation_date);

    /**
     * @api
     * @return string
     */
    public function getName();

    /**
     * @api
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * @api
     * @return string
     */
    public function getLastName();

    /**
     * @api
     * @param string $last_name
     * @return $this
     */
    public function setLastName($last_name);

    /**
     * @api
     * @return string
     */
    public function getEmail();

    /**
     * @api
     * @param string $email
     * @return $this
     */
    public function setEmail($email);

    /**
     * @api
     * @return string
     */
    public function getPhoneNumber();

    /**
     * @api
     * @param string $phone_number
     * @return $this
     */
    public function setPhoneNumber($phone_number);

    /**
     * @api
     * @return string
     */
    public function getExternalId();

    /**
     * @api
     * @param string $external_id
     * @return $this
     */
    public function setExternalId($external_id);

    /**
     * @api
     * @return string
     */
    public function getBalance();

    /**
     * @api
     * @param string $balance
     * @return $this
     */
    public function setBalance($balance);

    /**
     * @api
     * @return \Degaray\Openpay\Api\Data\AddressInterface
     */
    public function getAddress();

    /**
     * @api
     * @param \Degaray\Openpay\Api\Data\AddressInterface $address
     * @return $this
     */
    public function setAddress(AddressInterface $address);

    /**
     * @api
     * @return \Degaray\Openpay\Api\Data\StoreInterface
     */
    public function getStore();

    /**
     * @api
     * @param \Degaray\Openpay\Api\Data\StoreInterface $store
     * @return $this
     */
    public function setStore(StoreInterface $store);

    /**
     * @api
     * @return string
     */
    public function getClabe();

    /**
     * @api
     * @param string $clabe
     * @return $this
     */
    public function setClabe($clabe);
}
