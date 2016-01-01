<?php

namespace Degaray\Openpay\Model\Adapter;

use GuzzleHttp\Client;
use Magento\Framework\App\Config\ScopeConfigInterface;


/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 30/12/15
 * Time: 04:39 PM
 *
 * Class OpenpayClient
 * @package Degaray\Openpay\Model\Adapter
 */
class OpenpayClient extends Client
{
    public function __construct(ScopeConfigInterface $config)
    {
        $openpayConfigValues = $config->getValue('payment/openpay');
        $useSandbox = $openpayConfigValues['sandbox'];
        $baseUrl = ($useSandbox == true)? $openpayConfigValues['sandbox_url'] : $openpayConfigValues['production_url'];
        $params = [
            'base_uri' => $baseUrl
        ];

        parent::__construct($params);
    }
}