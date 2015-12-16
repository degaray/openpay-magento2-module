<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 15/12/15
 * Time: 06:25 PM
 */


namespace Degaray\Openpay\Model\Adapter;


// require_once '/var/www/mage2_vagrant/data/magento2/vendor/openpay/sdk/Openpay.php';
require_once 'vendor' .
        PATH_SEPARATOR .
        'openpay' .
        PATH_SEPARATOR .
        'sdk' . PATH_SEPARATOR .
        'Openpay.php';


/**
 * Class OpenpayConfig
 * @package Degaray\Openpay\Model\Adapter
 */
class OpenpayConfig
{
    /**
     * @param bool|false $value
     */
    public function setProductionMode($value = false)
    {
        \Openpay::setProductionMode($value);
    }

    /**
     * @param string|null $value
     */
    public function setId($value = null)
    {
        \Openpay::setId($value);
    }

    /**
     * @param string|null $value
     */
    public function setApiKey($value = null)
    {
        \Openpay::setApiKey($value);
    }

    /**
     * @param string|null $id
     * @param string|null $apiKey
     * @return mixed
     */
    public function getInstance($id = null, $apiKey = null)
    {
        if (!is_null($id)) {
            \Openpay::setId($id);
        }

        if (!is_null($apiKey)) {
            \Openpay::setApiKey($apiKey);
        }

        $openpay = \Openpay::getInstance($id, $apiKey);
        return $openpay;
    }
}