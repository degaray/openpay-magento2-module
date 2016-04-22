<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 4/22/16
 * Time: 12:05 PM
 */

namespace Degaray\Openpay\Model\Data;


use Degaray\Openpay\Api\Data\CredentialsInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class Credentials extends AbstractExtensibleModel implements CredentialsInterface
{
    /**
     * {@inheritdoc}
     */
    public function getMerchantId()
    {
        return $this->getData(self::MERCHANT_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setMerchantId($merchantId)
    {
        return $this->setData(self::MERCHANT_ID, $merchantId);
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicKey()
    {
        return $this->getData(self::PUBLIC_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function setPublicKey($publicKey)
    {
        return $this->setData(self::PUBLIC_KEY, $publicKey);
    }

    /**
     * {@inheritdoc}
     */
    public function isSandboxMode()
    {
        return $this->getData(self::IS_SANDBOX_MODE);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsSandboxMode($isSandboxMode)
    {
        return $this->setData(self::IS_SANDBOX_MODE, $isSandboxMode);
    }
}
