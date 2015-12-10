<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 27/11/15
 * Time: 01:13 PM
 */

namespace Degaray\Openpay\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $version = $context->getVersion();
        if (version_compare($version, '0.1.26') < 0) {
            $table = $setup->getConnection()
                ->newTable($setup->getTable('card_entity'))
                ->addColumn(
                    'entity_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'nullable' => false, 'primary' => true],
                    'Card ID'
                )
                ->addColumn(
                    'customer_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'unsigned' => true],
                    'Magento Customer Id'
                )
                ->addForeignKey(//$fkName, $column, $refTable, $refColumn, $onDelete = null
                    $setup->getFkName(
                        'openpay_magento_customer_id',
                        'customer_id',
                        'customer_entity',
                        'entity_id'
                    ),
                    'customer_id',
                    $setup->getTable('customer_entity'),
                    'entity_id',
                    Table::ACTION_CASCADE
                )
                ->addColumn(
                    'openpay_card_id',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Openpay Card Id'
                )
                ->addColumn('created_at', Table::TYPE_DATETIME, null, ['nullable' => false], 'Creation Time')
                ->addColumn('updated_at', Table::TYPE_DATETIME, null, ['nullable' => false], 'Update Time');
            $setup->getConnection()->createTable($table);
        }

        $setup->endSetup();
    }
}