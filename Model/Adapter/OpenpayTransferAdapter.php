<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 6/29/16
 * Time: 1:13 PM
 */

namespace Degaray\Openpay\Model\Adapter;

use GuzzleHttp\ClientInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Openpay\Client\Adapter\OpenpayCustomerTransfer as NonInjectableOpenpayFeeAdapter;
use Openpay\Client\Adapter\OpenpayCustomerTransferInterface;
use Openpay\Client\Mapper\OpenpayExceptionMapper;
use Openpay\Client\Mapper\OpenpayTransactionMapper;
use Openpay\Client\Validator\OpenpayTransferValidator;

class OpenpayTransferAdapter extends NonInjectableOpenpayFeeAdapter implements OpenpayCustomerTransferInterface
{
    public function __construct(
        ClientInterface $client,
        OpenpayExceptionMapper $exceptionMapper,
        OpenpayTransferValidator $validator,
        OpenpayTransactionMapper $transactionMapper,
        ScopeConfigInterface $scopeConfig,
        EncryptorInterface $encryptor
    ) {
        $paymentOpenpayConfig = $scopeConfig->getValue('payment/openpay');

        $paymentOpenpayConfig['merchantId'] = $encryptor->decrypt($paymentOpenpayConfig['merchantId']);
        $paymentOpenpayConfig['apiKey'] = $encryptor->decrypt($paymentOpenpayConfig['apiKey']);
        $paymentOpenpayConfig['publicKey'] = $encryptor->decrypt($paymentOpenpayConfig['publicKey']);

        parent::__construct($client, $exceptionMapper, $validator, $transactionMapper, $paymentOpenpayConfig);
    }
}
