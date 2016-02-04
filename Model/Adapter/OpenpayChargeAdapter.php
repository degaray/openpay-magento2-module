<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 29/01/16
 * Time: 04:16 PM
 */

namespace Degaray\Openpay\Model\Adapter;

use GuzzleHttp\ClientInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Openpay\Client\Adapter\OpenpayChargeAdapterInterface;
use Openpay\Client\Adapter\OpenpayChargeAdapter as NonInjectableOpenpayChargeAdapter;
use Openpay\Client\Mapper\OpenpayExceptionMapper;
use Openpay\Client\Mapper\OpenpayTransactionMapper;
use Openpay\Client\Validator\OpenpayChargeValidator;

class OpenpayChargeAdapter extends NonInjectableOpenpayChargeAdapter implements OpenpayChargeAdapterInterface
{
    public function __construct(
        ClientInterface $client,
        OpenpayExceptionMapper $exceptionMapper,
        OpenpayChargeValidator $validator,
        OpenpayTransactionMapper $transactionMapper,
        ScopeConfigInterface $config
    ) {
        $openpayConfigValues = $config->getValue('payment/openpay');
        parent::__construct($client, $exceptionMapper, $validator, $transactionMapper, $openpayConfigValues);
    }
}
