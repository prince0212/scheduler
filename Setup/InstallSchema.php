<?php
/**
 * MB "Vienas bitas" (Magetrend.com)
 *
 * @category MageTrend
 * @package  Magetend/Scheduler
 * @author   Edvinas St. <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     http://www.magetrend.com/magento-2-scheduler
 */

namespace Magetrend\Scheduler\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Install database schema class
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'mt_scheduler_schedule'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('mt_scheduler_schedule')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Schedule ID'
        )->addColumn(
            ScheduleInterface::TYPE,
            Table::TYPE_INTEGER,
            1,
            ['nullable' => false],
            'Type Id'
        )->addColumn(
            ScheduleInterface::STATUS,
            Table::TYPE_TEXT,
            50,
            ['nullable' => false],
            'Status'
        )->addColumn(
            ScheduleInterface::IS_ACTIVE,
            Table::TYPE_SMALLINT,
            1,
            ['nullable' => false],
            'Is Active'
        )->addColumn(
            ScheduleInterface::DATE_FROM,
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'Date From'
        )->addColumn(
            ScheduleInterface::DATE_TO,
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'Date To'
        )->addColumn(
            ScheduleInterface::NAME,
            Table::TYPE_TEXT,
            '256',
            [],
            'Name'
        )->addColumn(
            ScheduleInterface::DESCRIPTION,
            Table::TYPE_TEXT,
            null,
            [],
            'Description'
        )->addColumn(
            ScheduleInterface::PRODUCT_STATUS,
            Table::TYPE_SMALLINT,
            '1',
            ['nullable' => false, 'default' => 0],
            'Update product status'
        )->addColumn(
            ScheduleInterface::PRODUCT_SPECIAL_PRICE,
            Table::TYPE_SMALLINT,
            '1',
            ['nullable' => false, 'default' => 0],
            'Update product special price'
        )->addColumn(
            ScheduleInterface::PRODUCT_STOCK_STATUS,
            Table::TYPE_SMALLINT,
            '1',
            ['nullable' => false, 'default' => 0],
            'Update product stock status'
        )->addColumn(
            ScheduleInterface::PRODUCT_CATEGORIES,
            Table::TYPE_SMALLINT,
            '1',
            ['nullable' => false, 'default' => 0],
            'Update product categories'
        )->addColumn(
            ScheduleInterface::PRODUCT_SPECIAL_PRICE_ACTION,
            Table::TYPE_TEXT,
            '50',
            [],
            'Special Price Action'
        )->addColumn(
            ScheduleInterface::PRODUCT_SPECIAL_PRICE_VALUE,
            Table::TYPE_DECIMAL,
            '12,4',
            [],
            'Special Price Value'
        )->addColumn(
            ScheduleInterface::PRODUCT_NEW_STATUS,
            Table::TYPE_SMALLINT,
            '1',
            ['nullable' => false, 'default' => 0],
            'New Product Status'
        )->addColumn(
            ScheduleInterface::PRODUCT_NEW_STOCK_STATUS,
            Table::TYPE_SMALLINT,
            '1',
            ['nullable' => false, 'default' => 0],
            'New Product Stock Status'
        )->addColumn(
            ScheduleInterface::PRODUCT_ADD_TO_CATEGORIES,
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Add to categories IDs'
        )->addColumn(
            ScheduleInterface::PRODUCT_REMOVE_FROM_CATEGORIES,
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Remove from categories IDs'
        )->addColumn(
            ScheduleInterface::CMS_PAGE_NEW_STATUS,
            Table::TYPE_SMALLINT,
            1,
            ['nullable' => false],
            'CMS page new status'
        )->addColumn(
            ScheduleInterface::CMS_PAGE_STATUS,
            Table::TYPE_SMALLINT,
            1,
            ['nullable' => false],
            'Can change cms page status'
        )->addColumn(
            ScheduleInterface::CMS_BLOCK_NEW_STATUS,
            Table::TYPE_SMALLINT,
            1,
            ['nullable' => false],
            'CMS Block new status'
        )->addColumn(
            ScheduleInterface::CMS_BLOCK_STATUS,
            Table::TYPE_SMALLINT,
            1,
            ['nullable' => false],
            'Can change cms block status'
        )->addColumn(
            ScheduleInterface::CATEGORY_NEW_STATUS,
            Table::TYPE_SMALLINT,
            1,
            ['nullable' => false],
            'Category new status'
        )->addColumn(
            ScheduleInterface::CATEGORY_STATUS,
            Table::TYPE_SMALLINT,
            1,
            ['nullable' => false],
            'Can change category status'
        )->addColumn(
            ScheduleInterface::CONDITIONS,
            Table::TYPE_TEXT,
            '2M',
            [],
            'Conditions Serialized'
        )->setComment(
            'Schedules'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'mt_scheduler_schedule_product'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('mt_scheduler_schedule_product')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Relation ID'
        )->addColumn(
            'schedule_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, ],
            'Schedule Id'
        )->addColumn(
            'product_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, ],
            'Product Id'
        )->addIndex(
            $installer->getIdxName('mt_scheduler_schedule_product', ['schedule_id']),
            ['schedule_id']
        )->addIndex(
            $installer->getIdxName('mt_scheduler_schedule_product', ['product_id']),
            ['product_id']
        )->addForeignKey(
            $installer->getFkName(
                'mt_scheduler_schedule_product',
                'schedule_id',
                'mt_scheduler_schedule',
                'entity_id'
            ),
            'schedule_id',
            $installer->getTable('mt_scheduler_schedule'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName(
                'mt_scheduler_schedule_product',
                'product_id',
                'catalog_product_entity',
                'entity_id'
            ),
            'product_id',
            $installer->getTable('catalog_product_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->setComment(
            'Relation table between schedule and products'
        );

        $installer->getConnection()->createTable($table);

        /**
         * Create table 'mt_scheduler_schedule_cms_page'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('mt_scheduler_schedule_cms_page')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Relation ID'
        )->addColumn(
            'schedule_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, ],
            'Schedule Id'
        )->addColumn(
            'cms_page_id',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, ],
            'Cms Page Id'
        )->addIndex(
            $installer->getIdxName('mt_scheduler_schedule_cms_page', ['schedule_id']),
            ['schedule_id']
        )->addIndex(
            $installer->getIdxName('mt_scheduler_schedule_cms_page', ['cms_page_id']),
            ['cms_page_id']
        )->addForeignKey(
            $installer->getFkName(
                'mt_scheduler_schedule_cms_page',
                'schedule_id',
                'mt_scheduler_schedule',
                'entity_id'
            ),
            'schedule_id',
            $installer->getTable('mt_scheduler_schedule'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName(
                'mt_scheduler_schedule_cms_page',
                'cms_page_id',
                'cms_page',
                'page_id'
            ),
            'cms_page_id',
            $installer->getTable('cms_page'),
            'page_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->setComment(
            'Relation table between schedule and cms page'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'mt_scheduler_schedule_category'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('mt_scheduler_schedule_category')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Relation ID'
        )->addColumn(
            'schedule_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, ],
            'Schedule Id'
        )->addColumn(
            'category_id',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'unsigned' => true,],
            'Category Id'
        )->addIndex(
            $installer->getIdxName('mt_scheduler_schedule_category', ['schedule_id']),
            ['schedule_id']
        )->addIndex(
            $installer->getIdxName('mt_scheduler_schedule_category', ['category_id']),
            ['category_id']
        )->addForeignKey(
            $installer->getFkName(
                'mt_scheduler_schedule_category',
                'schedule_id',
                'mt_scheduler_schedule',
                'entity_id'
            ),
            'schedule_id',
            $installer->getTable('mt_scheduler_schedule'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName(
                'mt_scheduler_schedule_category',
                'category_id',
                'catalog_category_entity',
                'entity_id'
            ),
            'category_id',
            $installer->getTable('catalog_category_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->setComment(
            'Relation table between schedule and category'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'mt_scheduler_schedule_cms_block'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('mt_scheduler_schedule_cms_block')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Relation ID'
        )->addColumn(
            'schedule_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, ],
            'Schedule Id'
        )->addColumn(
            'cms_block_id',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, ],
            'Cms Block Id'
        )->addIndex(
            $installer->getIdxName('mt_scheduler_schedule_cms_block', ['schedule_id']),
            ['schedule_id']
        )->addIndex(
            $installer->getIdxName('mt_scheduler_schedule_cms_block', ['cms_block_id']),
            ['cms_block_id']
        )->addForeignKey(
            $installer->getFkName(
                'mt_scheduler_schedule_cms_block',
                'schedule_id',
                'mt_scheduler_schedule',
                'entity_id'
            ),
            'schedule_id',
            $installer->getTable('mt_scheduler_schedule'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName(
                'mt_scheduler_schedule_cms_block',
                'cms_block_id',
                'cms_block',
                'block_id'
            ),
            'cms_block_id',
            $installer->getTable('cms_block'),
            'block_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->setComment(
            'Relation table between schedule and cms block'
        );

        $installer->getConnection()->createTable($table);

        /**
         * Create table 'mt_scheduler_schedule_backup'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('mt_scheduler_schedule_backup')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Entity ID'
        )->addColumn(
            'schedule_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, ],
            'Schedule Id'
        )->addColumn(
            'related_object_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, ],
            'Related Object Id'
        );

        $table->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Store Id'
        );

        $table->addColumn(
            'backup_data',
            Table::TYPE_TEXT,
            '2M',
            ['nullable' => false],
            'Backup Data'
        )->addIndex(
            $installer->getIdxName('mt_scheduler_schedule_backup', ['schedule_id']),
            ['schedule_id']
        )->addIndex(
            $installer->getIdxName('mt_scheduler_schedule_backup', ['store_id']),
            ['store_id']
        )->addForeignKey(
            $setup->getFkName('mt_scheduler_schedule_backup', 'store_id', 'store', 'store_id'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName(
                'mt_scheduler_schedule_backup',
                'schedule_id',
                'mt_scheduler_schedule',
                'entity_id'
            ),
            'schedule_id',
            $installer->getTable('mt_scheduler_schedule'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Data backup table'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'mt_scheduler_schedule_store'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('mt_scheduler_schedule_store')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Entity ID'
        )->addColumn(
            'schedule_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, ],
            'Schedule Id'
        );

        $table->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Store Id'
        );

        $table->addIndex(
            $installer->getIdxName('mt_scheduler_schedule_store', ['schedule_id']),
            ['schedule_id']
        )->addIndex(
            $installer->getIdxName('mt_scheduler_schedule_store', ['store_id']),
            ['store_id']
        )->addForeignKey(
            $setup->getFkName('mt_scheduler_schedule_store', 'store_id', 'store', 'store_id'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName(
                'mt_scheduler_schedule_store',
                'schedule_id',
                'mt_scheduler_schedule',
                'entity_id'
            ),
            'schedule_id',
            $installer->getTable('mt_scheduler_schedule'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Schedule Store relation table'
        );

        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
