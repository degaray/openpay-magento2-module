<?php

namespace Degaray\Openpay\Model\Adapter;

use GuzzleHttp\ClientInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Openpay\Client\Adapter\OpenpayCardAdapter as NonInjectableCardAdapter;
use Openpay\Client\Adapter\OpenpayCardAdapterInterface;
use Openpay\Client\Mapper\OpenpayCardMapper;

/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 30/12/15
 * Time: 12:57 PM
 *
 * Class OpenpayCardAdapter
 * @package Degaray\Openpay\Model\Adapter
 */
class OpenpayCardAdapter extends NonInjectableCardAdapter implements OpenpayCardAdapterInterface
{
    public function __construct(
        ClientInterface $client,
        OpenpayCardMapper $cardMapper,
        ScopeConfigInterface $config
    ) {
        $paymentOpenpayConfig = $config->getValue('payment/openpay');
        parent::__construct($client, $cardMapper, $paymentOpenpayConfig);
    }
}
