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
use Magento\Framework\Encryption\EncryptorInterface;
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
        EncryptorInterface $encryptor,
        ScopeConfigInterface $config
    ) {
        $paymentOpenpayConfig = $config->getValue('payment/openpay');

        $paymentOpenpayConfig['merchantId'] = $encryptor->decrypt($paymentOpenpayConfig['merchantId']);
        $paymentOpenpayConfig['apiKey'] = $encryptor->decrypt($paymentOpenpayConfig['apiKey']);
        $paymentOpenpayConfig['publicKey'] = $encryptor->decrypt($paymentOpenpayConfig['publicKey']);
        
        parent::__construct($client, $exceptionMapper, $validator, $transactionMapper, $paymentOpenpayConfig);
    }
}
