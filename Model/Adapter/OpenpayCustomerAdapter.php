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
use Openpay\Client\Adapter\OpenpayCustomerAdapter as NonInjectableCustomerAdapter;
use Openpay\Client\Adapter\OpenpayCustomerAdapterInterface;
use Openpay\Client\Mapper\OpenpayCustomerMapper;
use Openpay\Client\Type\OpenpayCustomerType;
use Openpay\Client\Validator\OpenpayCustomerValidator;

class OpenpayCustomerAdapter extends NonInjectableCustomerAdapter implements OpenpayCustomerAdapterInterface
{
    public function __construct(
        OpenpayCustomerMapper $customerMapper,
        OpenpayCustomerType $customerType,
        ClientInterface $client,
        OpenpayCustomerValidator $customerValidator,
        ScopeConfigInterface $config
    ) {
        $paymentOpenpayConfig = $config->getValue('payment/openpay');
        parent::__construct($customerMapper, $customerType, $client, $customerValidator, $paymentOpenpayConfig);
    }

}