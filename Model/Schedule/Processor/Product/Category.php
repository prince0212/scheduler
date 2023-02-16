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

namespace Magetrend\Scheduler\Model\Schedule\Processor\Product;

use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Proessor class: update product categories
 */
class Category extends \Magetrend\Scheduler\Model\Schedule\Processor\Product
{
    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    public $dataObjectFactory;

    /**
     * @var \Magento\Catalog\Model\CategoryLinkRepository
     */
    public $categoryLinkRepository;

    /**
     * @var \Magento\Catalog\Api\CategoryLinkManagementInterface
     */
    public $categoryLinkManagement;

    /**
     * @var array
     */
    public $attributes = ['category_ids'];

    /**
     * Category constructor.
     * @param \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\DataObjectFactory $dataObjectFactory
     * @param \Magento\Catalog\Model\CategoryLinkRepository $categoryLinkRepository
     * @param \Magento\Catalog\Api\CategoryLinkManagementInterface $categoryLinkManagement
     * @param \Magento\Catalog\Model\ResourceModel\Product\Action $action
     * @param \Magento\Catalog\Model\ResourceModel\Product $productResource
     */
    public function __construct(
        \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Catalog\Model\CategoryLinkRepository $categoryLinkRepository,
        \Magento\Catalog\Api\CategoryLinkManagementInterface $categoryLinkManagement,
        \Magento\Catalog\Model\ResourceModel\Product\Action $action,
        \Magento\Catalog\Model\ResourceModel\Product $productResource
    ) {
        $this->dataObjectFactory = $dataObjectFactory;
        $this->categoryLinkRepository = $categoryLinkRepository;
        $this->categoryLinkManagement = $categoryLinkManagement;
        parent::__construct($scheduleResource, $productRepository, $action, $productResource);
    }

    /**
     * @param ScheduleInterface $schedule
     * @return bool
     */
    public function canProcess($schedule)
    {
        if (!$schedule->getData(ScheduleInterface::PRODUCT_CATEGORIES)) {
            return false;
        }
        return parent::canProcess($schedule);
    }

    /**
     * Update object data
     * @param $schedule
     * @param $product
     */
    public function process($schedule, $product)
    {
        $this->backupData($schedule, $product->getId(), $product, $this->attributes);
        $productId = $product->getId();

        $addCategories = $schedule->getData(ScheduleInterface::PRODUCT_ADD_TO_CATEGORIES);
        if (!empty($addCategories)) {
            $addCategories = is_string($addCategories)?explode(',', $addCategories):$addCategories;
            $schedule->getInstance()->registerCategory($productId, $addCategories);
        }

        $removeCategories = $schedule->getData(ScheduleInterface::PRODUCT_REMOVE_FROM_CATEGORIES);
        if (!empty($removeCategories)) {
            $removeCategories = is_string($removeCategories)?explode(',', $removeCategories):$removeCategories;
            $schedule->getInstance()->registerCategory($productId, $removeCategories, true);
        }
    }

    /**
     * Revert object data
     * @param $schedule
     * @param $product
     */
    public function revert($schedule, $product)
    {
        $backups = $schedule->getBackup();
        $storeId = $schedule->getStoreId();
        $productId = $product->getId();
        if (empty($backups) || !isset($backups[$product->getId()])
            || !isset($backups[$productId]['category_ids'])) {
            return;
        }

        $scheduleInstance = $schedule->getInstance();
        $productBackupCategories = $backups[$productId]['category_ids'];
        $addCategories = $schedule->getData(ScheduleInterface::PRODUCT_ADD_TO_CATEGORIES);
        if (!empty($addCategories)) {
            $addCategories = is_string($addCategories) ? explode(',', $addCategories) : $addCategories;
            $removeCategoryIds = [];
            foreach ($addCategories as $categoryId) {
                if (in_array($categoryId, $productBackupCategories)) {
                    /**
                     * The product was assigned to this category before
                     */
                    continue;
                }
                $removeCategoryIds[] = $categoryId;
            }

            $scheduleInstance->registerCategory($productId, $removeCategoryIds, true);
        }

        $removeCategories = $schedule->getData(ScheduleInterface::PRODUCT_REMOVE_FROM_CATEGORIES);
        if (!empty($removeCategories)) {
            $removeCategories = is_string($removeCategories)?explode(',', $removeCategories):$removeCategories;
            $addCategoryIds = [];
            foreach ($removeCategories as $categoryId) {
                if (!in_array($categoryId, $productBackupCategories)) {
                    /**
                     * The product was not assigned to this category before
                     */
                    continue;
                }

                $addCategoryIds[] = $categoryId;
            }

            $scheduleInstance->registerCategory($productId, $addCategoryIds);
        }
    }
}
