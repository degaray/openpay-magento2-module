<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 4/22/16
 * Time: 12:44 PM
 */

namespace Degaray\Openpay\Model;


use Degaray\Openpay\Api\Data\CredentialsInterfaceFactory;

use Degaray\Openpay\Api\Data\CredentialsInterface;
use Degaray\Openpay\Api\CredentialsRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;

class CredentialsRepository implements CredentialsRepositoryInterface
{
    const PAYMENT_OPENPAY_PATH = 'payment/openpay';

    /**
     * @var CredentialsInterfaceFactory
     */
    protected $credentialsFactory;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var EncryptorInterface
     */
    protected $encryptor;

    /**
     * CredentialsRepository constructor.
     * @param CredentialsInterfaceFactory $credentialsFactory
     * @param EncryptorInterface $encryptor
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        CredentialsInterfaceFactory $credentialsFactory,
        EncryptorInterface $encryptor,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->credentialsFactory = $credentialsFactory;
        $this->encryptor = $encryptor;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function get($storeId = null)
    {
        /** @var CredentialsInterface $credentials */
        $credentials = $this->credentialsFactory->create();

        $configValues = $this->scopeConfig->getValue(
            self::PAYMENT_OPENPAY_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $merchantId = $this->encryptor->decrypt($configValues['merchantId']);
        $publicKey = $this->encryptor->decrypt($configValues['publicKey']);
        $credentials->setMerchantId($merchantId)
            ->setPublicKey($publicKey)
            ->setIsSandboxMode($configValues['sandbox']);

        return $credentials;
    }
}
/**
<field id="active" translate="label" type="select" sortOrder="1" showInDefault="1" showInWebsite="1" showInStore="0">
                    <label>Enabled</label>
                    <source_model>Magento\Config\Model\Config\Source\Yesno</source_model>
                </field>
                <field id="merchantId" translate="label" type="obscure" sortOrder="10" showInDefault="1" showInWebsite="1" showInStore="1">
                    <label>ID de comercio</label>
                    <backend_model>Magento\Config\Model\Config\Backend\Encrypted</backend_model>
                </field>
                <field id="" translate="label" type="obscure" sortOrder="11" showInDefault="1" showInWebsite="1" showInStore="1">
                    <label>Llave privada</label>
                    <backend_model>Magento\Config\Model\Config\Backend\Encrypted</backend_model>
                </field>
                <field id="publicKey" translate="label" type="obscure" sortOrder="12" showInDefault="1" showInWebsite="1" showInStore="1">
                    <label>Llave pública</label>
                    <backend_model>Magento\Config\Model\Config\Backend\Encrypted</backend_model>
                </field>
                <field id="sandbox" translate="label" type="select" sortOrder="13" showInDefault="1" showInWebsite="1" showInStore="0">
                    <label>Modo pruebas (Sandbox)</label>
                    <source_model>Magento\Config\Model\Config\Source\Yesno</source_model>
                </field>
 * */