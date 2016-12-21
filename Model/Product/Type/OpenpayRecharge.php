<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 12/14/16
 * Time: 8:43 AM
 */

namespace Degaray\Openpay\Model\Product\Type;

use Magento\Catalog\Model\Product\Type\Virtual;

class OpenpayRecharge extends Virtual
{
    /**
     * Product type code
     */
    const TYPE_CODE = 'openpay_recharge';
}
