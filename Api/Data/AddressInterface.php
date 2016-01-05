<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 2/01/16
 * Time: 11:10 AM
 */

namespace Degaray\Openpay\Api\Data;


interface AddressInterface
{
    const CITY = 'city';
    const COUNTRY_CODE = 'country_code';
    const LINE1 = 'line1';
    const LINE2 = 'line2';
    const LINE3 = 'line3';
    const POSTAL_CODE = 'postal_code';
    const STATE = 'state';


    /**
     * @return string
     */
    public function getCity();

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city);

    /**
     * @return string
     */
    public function getCountryCode();

    /**
     * @param string $country_code
     * @return $this
     */
    public function setCountryCode($country_code);

    /**
     * @return string
     */
    public function getLine1();

    /**
     * @param string $line1
     * @return $this
     */
    public function setLine1($line1);

    /**
     * @return string
     */
    public function getLine2();

    /**
     * @param string $line2
     * @return $this
     */
    public function setLine2($line2);

    /**
     * @return string
     */
    public function getLine3();

    /**
     * @param string $line3
     * @return $this
     */
    public function setLine3($line3);

    /**
     * @return string
     */
    public function getPostalCode();

    /**
     * @param string $postal_code
     * @return $this
     */
    public function setPostalCode($postal_code);

    /**
     * @return string
     */
    public function getState();

    /**
     * @param string $state
     * @return $this
     */
    public function setState($state);
}
