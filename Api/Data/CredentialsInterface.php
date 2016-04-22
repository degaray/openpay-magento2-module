<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 4/22/16
 * Time: 12:00 PM
 */

namespace Degaray\Openpay\Api\Data;

/**
 * Interface CredentialsInterface
 * @package Degaray\Openpay\Api\Data
 */
interface CredentialsInterface
{
    const MERCHANT_ID = 'merchant_id';
    const PUBLIC_KEY = 'public_key';
    const IS_SANDBOX_MODE = 'is_sandbox_mode';

    /**
     * @api
     * @return string
     */
    public function getMerchantId();

    /**
     * @param string $merchantId
     * @return $this
     */
    public function setMerchantId($merchantId);

    /**
     * @api
     * @return string
     */
    public function getPublicKey();

    /**
     * @api
     * @return bool
     */
    public function isSandboxMode();

    /**
     * @param string $publicKey
     * @return $this
     */
    public function setPublicKey($publicKey);

    /**
     * @param bool $isSandboxMode
     * @return $this
     */
    public function setIsSandboxMode($isSandboxMode);
}
