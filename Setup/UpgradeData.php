<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 25/11/15
 * Time: 10:24 PM
 */

namespace Degaray\Openpay\Setup;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Address;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * From database table eav_entity_type
     */
    const CUSTOMER_EAV_ENTITY_TYPE = 'customer';

    /**
     * From database table eav_entity_type
     */
    const CUSTOMER_ADDRESS_EAV_ENTITY_TYPE = 'customer_address';

    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    public function __construct(CustomerSetupFactory $customerSetupFactory)
    {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * Upgrades data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $dbVersion = $context->getVersion();

        if (version_compare($dbVersion, '0.1.0', '<')) {
            /** @var CustomerSetup $customerSetup */
            /*$customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
            $customerSetup->addAttribute(
                self::CUSTOMER_EAV_ENTITY_TYPE,
                'openpay_customer_id',
                [
                    'label' => 'Openpay Customer ID',
                    'required' => 0,
                    'system' => 0,
                    'position' => 100
                ]
            );
            $customerSetup->getEavConfig()->getAttribute('customer', 'openpay_customer_id')
                ->setData('used_in_forms', ['adminhtml_customer'])
                ->save();*/

            $customerAddressSetup = $this->customerSetupFactory->create(['setup' => $setup]);
            $customerAddressSetup->addAttribute(
                self::CUSTOMER_ADDRESS_EAV_ENTITY_TYPE,
                'openpay_cc_id',
                [
                    'type' => 'text',
                    'label' => 'Openpay Credit Card ID',
                    'required' => 0,
                    'system' => 0,
                    'position' => 100
                ]
            );
            $customerAddressSetup->getEavConfig()->getAttribute(
                self::CUSTOMER_ADDRESS_EAV_ENTITY_TYPE,
                'openpay_cc_id'
            )->setData('used_in_forms', ['adminhtml_customer_address'])
            ->save();
        }
    }
}