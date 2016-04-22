<?php

namespace Degaray\Openpay\Model\Adapter;

use GuzzleHttp\ClientInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Openpay\Client\Adapter\OpenpayFeeAdapter as NonInjectableOpenpayFeeAdapter;
use Openpay\Client\Adapter\OpenpayFeeAdapterInterface;
use Openpay\Client\Mapper\OpenpayExceptionMapper;
use Openpay\Client\Mapper\OpenpayTransactionMapper;
use Openpay\Client\Validator\OpenpayFeeValidator;

/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 10/02/16
 * Time: 04:13 PM
 *
 * Class OpenpayFeeAdapter
 * @package Degaray\Openpay\Model\Adapter
 */
class OpenpayFeeAdapter extends NonInjectableOpenpayFeeAdapter implements OpenpayFeeAdapterInterface
{
    public function __construct(
        ClientInterface $client,
        OpenpayExceptionMapper $exceptionMapper,
        OpenpayFeeValidator $validator,
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
