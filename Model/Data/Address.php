<?php

namespace Degaray\Openpay\Model\Data;
use Degaray\Openpay\Api\Data\AddressInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 2/01/16
 * Time: 11:18 AM
 *
 * Class Address
 * @package Degaray\Openpay\Model\Data
 */
class Address extends AbstractModel implements AddressInterface
{
    /**
     * @return string
     */
    public function getCity()
    {
        return $this->getData(self::CITY);
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->setData(self::CITY, $city);
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->getData(self::COUNTRY_CODE);
    }

    /**
     * @param string $country_code
     * @return $this
     */
    public function setCountryCode($country_code)
    {
        $this->setData(self::COUNTRY_CODE, $country_code);
        return $this;
    }

    /**
     * @return string
     */
    public function getLine1()
    {
        return $this->getData(self::LINE1);
    }

    /**
     * @param string $line1
     * @return $this
     */
    public function setLine1($line1)
    {
        $this->setData(self::LINE1, $line1);
        return $this;
    }

    /**
     * @return string
     */
    public function getLine2()
    {
        return $this->getData(self::LINE2);
    }

    /**
     * @param string $line2
     * @return $this
     */
    public function setLine2($line2)
    {
        $this->setData(self::LINE2, $line2);
        return $this;
    }

    /**
     * @return string
     */
    public function getLine3()
    {
        return $this->getData(self::LINE3);
    }

    /**
     * @param string $line3
     * @return $this
     */
    public function setLine3($line3)
    {
        $this->setData(self::LINE3, $line3);
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->getData(self::POSTAL_CODE);
    }

    /**
     * @param string $postal_code
     * @return $this
     */
    public function setPostalCode($postal_code)
    {
        $this->setData(self::POSTAL_CODE, $postal_code);
        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->getData(self::STATE);
    }

    /**
     * @param string $state
     * @return $this
     */
    public function setState($state)
    {
        $this->setData(self::STATE, $state);
        return $this;
    }
}
