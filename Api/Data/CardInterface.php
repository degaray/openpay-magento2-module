<?php
namespace Degaray\Openpay\Api\Data;

use \Magento\Customer\Api\Data\AddressInterface;
use \Magento\Framework\Api\ExtensibleDataInterface;
use \DateTime;
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 30/11/15
 * Time: 01:39 PM
 *
 * Interface CardInterface
 * @package Degaray\Openpay\Api\Data
 */
interface CardInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID = 'entity_id';
    const CUSTOMER_ID = 'customer_id';
    const CARD_ID = 'card_id';
    const TOKEN = 'token';
    const DEVICE_SESSION_ID = 'device_session_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const TYPE = 'type';
    const BRAND = 'brand';
    const CARD_NUMBER = 'card_number';
    const HOLDER_NAME = 'holder_name';
    const EXPIRATION_YEAR = 'expiration_year';
    const EXPIRATION_MONTH = 'expiration_month';
    const ALLOWS_CHARGES = 'allows_charges';
    const ALLOWS_PAYOUTS = 'allows_payouts';
    const BANK_NAME = 'bank_name';
    const BANK_CODE = 'bank_code';

    const ADDRESS = 'address';

    /**#@-*/

    /**
     * Get ID
     *
     * @api
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @api
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get customer ID
     *
     * @api
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Set customer ID
     *
     * @api
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId);


    /**
     * Get Card ID
     *
     * @api
     * @return string|null
     */
    public function getCardId();

    /**
     * Set Card ID
     *
     * @api
     * @param string $cardId
     * @return $this
     */
    public function setCardId($cardId);

    /**
     * Get creation at datetime
     * @return DateTime
     */
    public function getCreatedAt();

    /**
     * Set creation time
     *
     * @param DateTime $created_at
     * @return $this
     */
    public function setCreatedAt($created_at);

    /**
     * Get updated at datetime
     * @return DateTime
     */
    public function getUpdatedAt();

    /**
     * Set updated at time
     *
     * @param DateTime $updated_at
     * @return $this
     */
    public function setUpdatedAt($updated_at);

    /**
     * @return string
     */
    public function getToken();

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token);

    /**
     * @return string
     */
    public function getDeviceSessionId();

    /**
     * @param string $session_id
     * @return $this
     */
    public function setDeviceSessionId($session_id);

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getBrand();

    /**
     * @return string
     */
    public function getCardNumber();

    /**
     * @return string
     */
    public function getHolderName();

    /**
     * @return string
     */
    public function getExpirationYear();

    /**
     * @return string
     */
    public function getExpirationMonth();

    /**
     * @return bool
     */
    public function getAllowsCharges();

    /**
     * @return bool
     */
    public function getAllowsPayouts();

    /**
     * @return string
     */
    public function getBankName();

    /**
     * @return string
     */
    public function getBankCode();

    /**
     * @param string $type
     * @return string
     */
    public function setType($type);

    /**
     * @param string $brand
     * @return string
     */
    public function setBrand($brand);

    /**
     * @param string $card_number
     * @return string
     */
    public function setCardNumber($card_number);

    /**
     * @param string $holder_name
     * @return string
     */
    public function setHolderName($holder_name);

    /**
     * @param string $expiration_year
     * @return string
     */
    public function setExpirationYear($expiration_year);

    /**
     * @param string $expiration_month
     * @return string
     */
    public function setExpirationMonth($expiration_month);

    /**
     * @param bool $allows_charges
     * @return bool
     */
    public function setAllowsCharges($allows_charges);

    /**
     * @param bool $allows_payouts
     * @return bool
     */
    public function setAllowsPayouts($allows_payouts);

    /**
     * @param string $bank_name
     * @return string
     */
    public function setBankName($bank_name);

    /**
     * @param string $bank_code
     * @return string
     */
    public function setBankCode($bank_code);

    /**
     * @return \Magento\Customer\Api\Data\AddressInterface
     */
    public function getAddress();

    /**
     * @param \Magento\Customer\Api\Data\AddressInterface $address
     * @return \Magento\Customer\Api\Data\AddressInterface
     */
    public function setAddress(AddressInterface $address);
}
