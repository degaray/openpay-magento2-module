<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 16/12/15
 * Time: 10:19 AM
 */

namespace Degaray\Openpay\Model\Method;


use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Payment\Model\Method\Cc;

class OpenpayPaymentMethod extends Cc
{
    const METHOD_CODE = 'openpay';

    public function validate()
    {
        parent::validate();

        $paymentInfo = $this->getInfoInstance();

    }
}