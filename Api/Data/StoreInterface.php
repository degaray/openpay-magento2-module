<?php
namespace Degaray\Openpay\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 4/02/16
 * Time: 11:18 AM
 *
 * Interface StoreInterface
 * @package Degaray\Openpay\Api\Data
 */
interface StoreInterface extends ExtensibleDataInterface
{
    const REFERENCE = 'reference';
    const BARCODE_URL = 'barcode_url';

    /**
     * @api
     * @return string
     */
    public function getReference();

    /**
     * @api
     * @param string $reference
     * @return $this
     */
    public function setReference($reference);

    /**
     * @api
     * @return string
     */
    public function getBarcodeUrl();

    /**
     * @api
     * @param string $barcode_url
     * @return $this
     */
    public function setBarcodeUrl($barcode_url);
}
