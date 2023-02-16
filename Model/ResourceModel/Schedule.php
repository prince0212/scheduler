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

namespace Magetrend\Scheduler\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Schedule resource class
 */
class Schedule extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var array
     */
    private $productsCache = [];

    /**
     * @var array
     */
    private $cmsPageCache = [];

    /**
     * @var array
     */
    private $cmsBlockCache = [];

    /**
     * @var array
     */
    private $categoryCache = [];

    /**
     * @var array
     */
    private $storesCache = [];

    /**
     * @var array
     */
    private $backupCache = [];

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mt_scheduler_schedule', 'entity_id');
    }

    /**
     * @param AbstractModel $object
     * @return $this
     */
    protected function _afterSave(AbstractModel $object)
    {
        $this->saveProducts($object)
            ->saveStores($object)
            ->saveCmsPages($object)
            ->saveCmsBlock($object)
            ->saveCategory($object);
        return parent::_afterSave($object);
    }

    /**
     * Returns assigned sotre ids
     * @param $scheduleId
     * @return array
     */
    public function getStoresByScheduleId($scheduleId)
    {
        if (!isset($this->storesCache[$scheduleId])) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(
                $this->getTable('mt_scheduler_schedule_store'),
                ['store_id']
            )->where('schedule_id =?', $scheduleId);

            $this->storesCache[$scheduleId] = $connection->fetchCol($select);
        }

        return $this->storesCache[$scheduleId];
    }

    /**
     * Returns assigned products ids
     * @param $scheduleId
     * @return array
     */
    public function getProductsByScheduleId($scheduleId)
    {
        if (!isset($this->productsCache[$scheduleId])) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(
                $this->getTable('mt_scheduler_schedule_product'),
                ['product_id']
            )->where('schedule_id =?', $scheduleId);

            $this->productsCache[$scheduleId] = $connection->fetchCol($select);
        }

        return $this->productsCache[$scheduleId];
    }

    /**
     * Returns assigned cms page ids
     * @param $scheduleId
     * @return array
     */
    public function getCmsPageByScheduleId($scheduleId)
    {
        if (!isset($this->cmsPageCache[$scheduleId])) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(
                $this->getTable('mt_scheduler_schedule_cms_page'),
                ['cms_page_id']
            )->where('schedule_id =?', $scheduleId);

            $this->cmsPageCache[$scheduleId] = $connection->fetchCol($select);
        }

        return $this->cmsPageCache[$scheduleId];
    }

    /**
     * Returns assigned cms block ids
     * @param $scheduleId
     * @return array
     */
    public function getCmsBlockByScheduleId($scheduleId)
    {
        if (!isset($this->cmsBlockCache[$scheduleId])) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(
                $this->getTable('mt_scheduler_schedule_cms_block'),
                ['cms_block_id']
            )->where('schedule_id =?', $scheduleId);

            $this->cmsBlockCache[$scheduleId] = $connection->fetchCol($select);
        }

        return $this->cmsBlockCache[$scheduleId];
    }

    /**
     * Returns assigned category ids
     * @param $scheduleId
     * @return array
     */
    public function getCategoryByScheduleId($scheduleId)
    {
        if (!isset($this->categoryCache[$scheduleId])) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(
                $this->getTable('mt_scheduler_schedule_category'),
                ['category_id']
            )->where('schedule_id =?', $scheduleId);

            $this->categoryCache[$scheduleId] = $connection->fetchCol($select);
        }

        return $this->categoryCache[$scheduleId];
    }

    /**
     * Returns schedule backup data
     * @param int $scheduleId
     * @return mixed
     */
    public function getBackupScheduleId($scheduleId)
    {
        if (!isset($this->backupCache[$scheduleId])) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(
                $this->getTable('mt_scheduler_schedule_backup'),
                ['related_object_id', 'store_id', 'backup_data']
            )->where('schedule_id =?', $scheduleId);

            $this->backupCache[$scheduleId] = $connection->fetchAll($select);
        }

        return $this->backupCache[$scheduleId];
    }

    /**
     * Save assigned products
     * @param AbstractModel $object
     * @return $this
     */
    public function saveProducts(AbstractModel $object)
    {
        if (!$object->hasData(ScheduleInterface::PRODUCT_IDS)) {
            return $this;
        }

        $productIds = $object->getData(ScheduleInterface::PRODUCT_IDS);
        $scheduleId = $object->getId();
        if (is_array($productIds)) {
            $connection = $this->getConnection();
            if ($object->getId()) {
                $condition = ['schedule_id =?' => $scheduleId];
                $connection->delete($this->getTable('mt_scheduler_schedule_product'), $condition);
            }

            foreach ($productIds as $productId) {
                $bind = ['schedule_id' => $scheduleId, 'product_id' => $productId];
                $connection->insert($this->getTable('mt_scheduler_schedule_product'), $bind);
            }
        }

        return $this;
    }

    /**
     * Save assigned cms pages
     * @param AbstractModel $object
     * @return $this
     */
    public function saveCmsPages(AbstractModel $object)
    {
        if (!$object->hasData(ScheduleInterface::CMS_PAGE_IDS)) {
            return $this;
        }

        $cmsPageIds = $object->getData(ScheduleInterface::CMS_PAGE_IDS);
        $scheduleId = $object->getId();
        if (is_array($cmsPageIds)) {
            $connection = $this->getConnection();
            if ($object->getId()) {
                $condition = ['schedule_id =?' => $scheduleId];
                $connection->delete($this->getTable('mt_scheduler_schedule_cms_page'), $condition);
            }

            foreach ($cmsPageIds as $pageId) {
                $bind = ['schedule_id' => $scheduleId, 'cms_page_id' => $pageId];
                $connection->insert($this->getTable('mt_scheduler_schedule_cms_page'), $bind);
            }
        }

        return $this;
    }

    /**
     * Save assigned cms blocks
     * @param AbstractModel $object
     * @return $this
     */
    public function saveCmsBlock(AbstractModel $object)
    {
        if (!$object->hasData(ScheduleInterface::CMS_BLOCK_IDS)) {
            return $this;
        }

        $cmsBlockIds = $object->getData(ScheduleInterface::CMS_BLOCK_IDS);
        $scheduleId = $object->getId();
        if (is_array($cmsBlockIds)) {
            $connection = $this->getConnection();
            if ($object->getId()) {
                $condition = ['schedule_id =?' => $scheduleId];
                $connection->delete($this->getTable('mt_scheduler_schedule_cms_block'), $condition);
            }

            foreach ($cmsBlockIds as $blockId) {
                $bind = ['schedule_id' => $scheduleId, 'cms_block_id' => $blockId];
                $connection->insert($this->getTable('mt_scheduler_schedule_cms_block'), $bind);
            }
        }

        return $this;
    }

    /**
     * Save assigned categories
     * @param AbstractModel $object
     * @return $this
     */
    public function saveCategory(AbstractModel $object)
    {
        if (!$object->hasData(ScheduleInterface::CATEGORY_IDS)) {
            return $this;
        }

        $categoryIds = $object->getData(ScheduleInterface::CATEGORY_IDS);
        $scheduleId = $object->getId();
        if (is_string($categoryIds) && !empty($categoryIds)) {
            $categoryIds = explode(',', $categoryIds);
        }
        if (is_array($categoryIds)) {
            $connection = $this->getConnection();
            if ($object->getId()) {
                $condition = ['schedule_id =?' => $scheduleId];
                $connection->delete($this->getTable('mt_scheduler_schedule_category'), $condition);
            }

            foreach ($categoryIds as $categoryId) {
                $bind = ['schedule_id' => $scheduleId, 'category_id' => $categoryId];
                $connection->insert($this->getTable('mt_scheduler_schedule_category'), $bind);
            }
        }

        return $this;
    }

    /**
     * Save assigend stores
     * @param AbstractModel $object
     * @return $this
     */
    public function saveStores(AbstractModel $object)
    {
        if (!$object->hasData(ScheduleInterface::STORE_IDS)) {
            return $this;
        }

        $storeIds = $object->getStoreIds();
        $scheduleId = $object->getId();
        if (is_array($storeIds)) {
            $connection = $this->getConnection();
            if ($object->getId()) {
                $condition = ['schedule_id =?' => $scheduleId];
                $connection->delete($this->getTable('mt_scheduler_schedule_store'), $condition);
            }

            foreach ($storeIds as $storeId) {
                $bind = ['schedule_id' => $scheduleId, 'store_id' => $storeId];
                $connection->insert($this->getTable('mt_scheduler_schedule_store'), $bind);
            }
        }

        return $this;
    }

    /**
     * Save backup data
     * @param AbstractModel $object
     * @return $this
     */
    public function saveBackup(AbstractModel $object)
    {
        $backups = $object->getBackup();
        $scheduleId = $object->getId();
        $storeId = $object->getStoreId();
        if (empty($backups) || !$scheduleId) {
            return $this;
        }

        $connection = $this->getConnection();
        $condition = [
            'schedule_id =?' => $scheduleId,
            'store_id =?' => $storeId,
        ];

        $connection->delete($this->getTable('mt_scheduler_schedule_backup'), $condition);

        foreach ($backups as $relatedObjectId => $data) {
            $bind = [
                'schedule_id' => $scheduleId,
                'store_id' => $storeId,
                'related_object_id' => $relatedObjectId,
                'backup_data' => json_encode($data),
            ];
            $connection->insert($this->getTable('mt_scheduler_schedule_backup'), $bind);
        }

        return $this;
    }
}
