<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 13/01/16
 * Time: 10:13 PM
 */

namespace Degaray\Openpay\Api;

interface CredentialsRepositoryInterface
{
    /**
     * @return \Degaray\Openpay\Api\Data\CredentialsInterface
     */
    public function get();
}
