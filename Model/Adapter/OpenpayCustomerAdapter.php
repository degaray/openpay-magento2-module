<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 30/12/15
 * Time: 12:04 PM
 */

namespace Degaray\Openpay\Model\Adapter;

use GuzzleHttp\ClientInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Openpay\Client\Adapter\OpenpayCustomerAdapter as NonInjectableCustomerAdapter;
use Openpay\Client\Adapter\OpenpayCustomerAdapterInterface;
use Openpay\Client\Mapper\OpenpayCustomerMapper;
use Openpay\Client\Mapper\OpenpayExceptionMapper;
use Openpay\Client\Type\OpenpayCustomerType;
use Openpay\Client\Validator\OpenpayCustomerValidator;

class OpenpayCustomerAdapter extends NonInjectableCustomerAdapter implements OpenpayCustomerAdapterInterface
{
    public function __construct(
        OpenpayCustomerMapper $customerMapper,
        OpenpayCustomerType $customerType,
        ClientInterface $client,
        OpenpayCustomerValidator $customerValidator,
        OpenpayExceptionMapper $exceptionMapper,
        EncryptorInterface $encryptor,
        ScopeConfigInterface $config
    ) {
        $paymentOpenpayConfig = $config->getValue('payment/openpay');

        $paymentOpenpayConfig['merchantId'] = $encryptor->decrypt($paymentOpenpayConfig['merchantId']);
        $paymentOpenpayConfig['apiKey'] = $encryptor->decrypt($paymentOpenpayConfig['apiKey']);
        $paymentOpenpayConfig['publicKey'] = $encryptor->decrypt($paymentOpenpayConfig['publicKey']);
        parent::__construct(
            $customerMapper,
            $customerType,
            $client,
            $customerValidator,
            $exceptionMapper,
            $paymentOpenpayConfig
        );
    }

}