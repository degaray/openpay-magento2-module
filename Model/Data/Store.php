<?php

namespace Degaray\Openpay\Model\Data;

use Degaray\Openpay\Api\Data\StoreInterface;
use Magento\Framework\Model\AbstractModel;


/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 4/02/16
 * Time: 11:33 AM
 *
 * Class Store
 * @package Degaray\Openpay\Model\Data
 */
class Store extends AbstractModel implements StoreInterface
{
    /**
     * @var string
     */
    protected $reference;

    /**
     * @var string
     */
    protected $barcode_url;

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->getData(self::REFERENCE);
    }

    /**
     * @param string $reference
     * @return $this
     */
    public function setReference($reference)
    {
        $this->setData(self::REFERENCE, $reference);
        return $this;
    }

    /**
     * @return string
     */
    public function getBarcodeUrl()
    {
        return $this->getData(self::BARCODE_URL);
    }

    /**
     * @param string $barcode_url
     * @return $this
     */
    public function setBarcodeUrl($barcode_url)
    {
        $this->setData(self::BARCODE_URL, $barcode_url);
        return $this;
    }
}
