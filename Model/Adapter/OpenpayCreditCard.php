<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 16/12/15
 * Time: 11:11 AM
 */

namespace Degaray\Openpay\Model\Adapter;


use Degaray\Openpay\Model\Config;

class OpenpayCreditCard
{
    /**
     * @var
     */
    protected $openpay;

    /**
     * OpenpayCreditCard constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->openpay = $config->getOpenpay();
    }

    /**
     * @param array $findData
     * $findData = array(
     *    'creation[gte]' => '2013-01-01',
     *    'creation[lte]' => '2013-12-31',
     *    'offset' => 0,
     *    'limit' => 5);
     */
    public function getList(array $findData = [])
    {
        $cardList = $this->openpay->cards->getList($findData);
        return $cardList;
    }

    /**
     * @param $customer
     * @param array $findData
     * @return mixed
     */
    public function getCustomerCardsList($customer, array $findData = [])
    {
        $openpayCustomer = $this->openpay->customers->get($customer);
        $cardsList = $openpayCustomer->cards->getList($findData);

        return $cardsList;
    }
}