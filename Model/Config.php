<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 15/12/15
 * Time: 06:08 PM
 */

namespace Degaray\Openpay\Model;

use Degaray\Openpay\Model\Adapter\OpenpayConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 * @package Degaray\Openpay\Model\Adapter
 */
class Config
{
    const PAYMENT_BASE_PATH = 'payment/';
    const SANDBOX_CONFIG_FIELD = 'sandbox';
    const MERCHANT_ID_CONFIG_FIELD = 'merchant_id';
    const PRIVATE_KEY_CONFIG_FIELD = 'private_key';
    const PUBLIC_KEY_CONFIG_FIELD = 'public_key';
    const KEY_ACTIVE = 'active';
    const METHOD_CODE = 'openpay';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var OpenpayConfig
     */
    protected $openpayConfig;

    /**
     * @var int
     */
    protected $storeId;

    /**
     * @var mixed
     */
    protected $openpay;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param OpenpayConfig $openpayConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        OpenpayConfig $openpayConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->openpayConfig = $openpayConfig;

        if ($this->getConfigData(self::KEY_ACTIVE) == 1) {
            $this->initEnvironment(null);
        }
    }

    /**
     * @param int $storeId
     * @return $this
     */
    public function initEnvironment($storeId)
    {
        $configSandboxValue = $this->getConfigData(self::SANDBOX_CONFIG_FIELD, $storeId);
        $this->openpayConfig->setProductionMode($configSandboxValue);

        $this->storeId = $storeId;

        $merchantId = $this->getConfigData(self::MERCHANT_ID_CONFIG_FIELD, $storeId);
        $privateKey = $this->getConfigData(self::PRIVATE_KEY_CONFIG_FIELD, $storeId);

        $this->openpay = $this->openpayConfig->getInstance($merchantId, $privateKey);

        return $this;
    }

    /**
     * @param $field
     * @param null $storeId
     * @return mixed
     */
    public function getConfigData($field, $storeId = null)
    {
        if ($storeId == null) {
            $storeId = $this->storeId;
        }

        $path = self::PAYMENT_BASE_PATH . self::METHOD_CODE . '/' . $field;
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @return mixed
     */
    public function getOpenpay()
    {
        return $this->openpay;
    }
}