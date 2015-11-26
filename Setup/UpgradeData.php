<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 25/11/15
 * Time: 10:24 PM
 */

namespace Degaray\Openpay\Setup;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
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
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
            $customerSetup->addAttribute(
                Customer::ENTITY,
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
                ->save();
        }
    }
}