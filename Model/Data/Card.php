<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 4/12/15
 * Time: 04:35 PM
 */

namespace Degaray\Openpay\Model\Data;

use Degaray\Openpay\Api\Data\CardInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Framework\Model\AbstractModel;
use \DateTime;

/**
 * Class Card
 * @package Degaray\Openpay\Model\Data
 */
class Card extends AbstractModel implements CardInterface
{
    /**
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->setData(self::ENTITY_ID, $id);
        return $this;
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        $this->setData(self::CUSTOMER_ID, $customerId);
        return $this;
    }

    /**
     * @return string
     */
    public function getCardId()
    {
        return $this->getData(self::CARD_ID);
    }

    /**
     * @param string $cardId
     * @return $this
     */
    public function setCardId($cardId)
    {
        $this->setData(self::CARD_ID, $cardId);
        return $this;
    }

    /**
     * @return Datetime
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @param DateTime $created_at
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->setData(self::CREATED_AT, $created_at);
        return $this;
    }

    /**
     * @return Datetime
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @param DateTime $updated_at
     * @return $this
     */
    public function setUpdatedAt($updated_at)
    {
        $this->setData(self::UPDATED_AT, $updated_at);
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->getData(self::TOKEN);
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->setData(self::TOKEN, $token);
        return $this;
    }

    /**
     * @return string
     */
    public function getDeviceSessionId()
    {
        return $this->getData(self::DEVICE_SESSION_ID);
    }

    /**
     * @param string $session_id
     * @return $this
     */
    public function setDeviceSessionId($session_id)
    {
        $this->setData(self::DEVICE_SESSION_ID, $session_id);
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->getData(self::BRAND);
    }

    /**
     * @return string
     */
    public function getCardNumber()
    {
        return $this->getData(self::CARD_NUMBER);
    }

    /**
     * @return string
     */
    public function getHolderName()
    {
        return $this->getData(self::HOLDER_NAME);
    }

    /**
     * @return string
     */
    public function getExpirationYear()
    {
        return $this->getData(self::EXPIRATION_YEAR);
    }

    /**
     * @return string
     */
    public function getExpirationMonth()
    {
        return $this->getData(self::EXPIRATION_MONTH);
    }

    /**
     * @return bool
     */
    public function getAllowsCharges()
    {
        return $this->getData(self::ALLOWS_CHARGES);
    }

    /**
     * @return bool
     */
    public function getAllowsPayouts()
    {
        return $this->getData(self::ALLOWS_PAYOUTS);
    }

    /**
     * @return string
     */
    public function getBankName()
    {
        return $this->getData(self::BANK_NAME);
    }

    /**
     * @return string
     */
    public function getBankCode()
    {
        return $this->getData(self::BANK_CODE);
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->setData(self::TYPE, $type);
        return $this;
    }

    /**
     * @param string $brand
     * @return $this
     */
    public function setBrand($brand)
    {
        $this->setData(self::BRAND, $brand);
        return $this;
    }

    /**
     * @param string $card_number
     * @return $this
     */
    public function setCardNumber($card_number)
    {
        $this->setData(self::CARD_NUMBER, $card_number);
        return $this;
    }

    /**
     * @param string $holder_name
     * @return $this
     */
    public function setHolderName($holder_name)
    {
        $this->setData(self::HOLDER_NAME, $holder_name);
        return $this;
    }

    /**
     * @param string $expiration_year
     * @return $this
     */
    public function setExpirationYear($expiration_year)
    {
        $this->setData(self::EXPIRATION_YEAR, $expiration_year);
        return $this;
    }

    /**
     * @param string $expiration_month
     * @return $this
     */
    public function setExpirationMonth($expiration_month)
    {
        $this->setData(self::EXPIRATION_MONTH, $expiration_month);
        return $this;
    }

    /**
     * @param bool $allows_charges
     * @return $this
     */
    public function setAllowsCharges($allows_charges)
    {
        $this->setData(self::ALLOWS_CHARGES, $allows_charges);
        return $this;
    }

    /**
     * @param bool $allows_payouts
     * @return $this
     */
    public function setAllowsPayouts($allows_payouts)
    {
        $this->setData(self::ALLOWS_PAYOUTS, $allows_payouts);
        return $this;
    }

    /**
     * @param string $bank_name
     * @return $this
     */
    public function setBankName($bank_name)
    {
        $this->setData(self::BANK_NAME, $bank_name);
        return $this;
    }

    /**
     * @param string $bank_code
     * @return $this
     */
    public function setBankCode($bank_code)
    {
        $this->setData(self::BANK_CODE, $bank_code);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->getData(self::ADDRESS);
    }

    /**
     * @param mixed $address
     * @return $this
     */
    public function setAddress(AddressInterface $address)
    {
        $this->setData(self::ADDRESS, $address);
        return $this;
    }
}
