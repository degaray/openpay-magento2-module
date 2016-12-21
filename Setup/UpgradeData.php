<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 25/11/15
 * Time: 10:24 PM
 */

namespace Degaray\Openpay\Setup;

use Degaray\Openpay\Model\Product\Type\OpenpayRecharge;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Address;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Setup\EavSetupFactory;

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

    const OPENPAY_CUSTOMER_ID_CUSTOM_ATTRIBUTE = 'openpay_customer_id';

    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * UpgradeData constructor.
     * @param CustomerSetupFactory $customerSetupFactory
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->eavSetupFactory = $eavSetupFactory;
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
                self::CUSTOMER_EAV_ENTITY_TYPE,
                self::OPENPAY_CUSTOMER_ID_CUSTOM_ATTRIBUTE,
                [
                    'label' => 'Openpay Customer ID',
                    'required' => 0,
                    'system' => 0,
                    'position' => 100
                ]
            );
            $customerSetup->getEavConfig()->getAttribute('customer', self::OPENPAY_CUSTOMER_ID_CUSTOM_ATTRIBUTE)
                ->setData('used_in_forms', ['adminhtml_customer'])
                ->save();
        }

        if (version_compare($dbVersion, '0.1.1', '<')) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            $fieldList = [
                'price',
                'special_price',
                'special_from_date',
                'special_to_date',
                'minimal_price',
                'cost',
                'tier_price',
            ];

            // make these attributes applicable to downloadable products
            foreach ($fieldList as $field) {
                $applyTo = explode(
                    ',',
                    $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $field, 'apply_to')
                );
                if (!in_array(OpenpayRecharge::TYPE_CODE, $applyTo)) {
                    $applyTo[] = OpenpayRecharge::TYPE_CODE;
                    $eavSetup->updateAttribute(
                        \Magento\Catalog\Model\Product::ENTITY,
                        $field,
                        'apply_to',
                        implode(',', $applyTo)
                    );
                }
            }
        }
    }
}