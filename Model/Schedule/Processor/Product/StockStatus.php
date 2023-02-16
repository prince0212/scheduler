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

use Magento\Catalog\Api\Data\ProductInterface;
use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Proessor class: Update product stock status
 */
class StockStatus extends \Magetrend\Scheduler\Model\Schedule\Processor\Product
{
    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    public $stockRegistry;

    /**
     * @var \Magento\CatalogInventory\Api\StockRepositoryInterface
     */
    public $stockRepository;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    public $dataObjectFactory;

    /**
     * StockStatus constructor.
     * @param \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\CatalogInventory\Api\StockRepositoryInterface $stockRepository
     * @param \Magento\Framework\DataObjectFactory $dataObjectFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\Action $action
     * @param \Magento\Catalog\Model\ResourceModel\Product $productResource
     */
    public function __construct(
        \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\CatalogInventory\Api\StockRepositoryInterface $stockRepository,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Action $action,
        \Magento\Catalog\Model\ResourceModel\Product $productResource
    ) {
        $this->stockRegistry = $stockRegistry;
        $this->stockRepository = $stockRepository;
        $this->dataObjectFactory = $dataObjectFactory;
        parent::__construct($scheduleResource, $productRepository, $action, $productResource);
    }

    /**
     * @param ScheduleInterface $schedule
     * @return bool
     */
    public function canProcess($schedule)
    {
        if (!$schedule->getData(ScheduleInterface::PRODUCT_STOCK_STATUS)) {
            return false;
        }

        return parent::canProcess($schedule);
    }

    /**
     * Update object data
     * @param $schedule
     * @param ProductInterface $product
     */
    public function process($schedule, $product)
    {
        $sku = $product->getSku();
        $storeId = $schedule->getStoreId();
        $stockItem = $this->stockRegistry->getStockItemBySku($sku, $storeId);

        $dataObject = $this->dataObjectFactory->create()
            ->setData([
                'is_in_stock' =>  $stockItem->getIsInStock()?1:0
            ]);

        $this->backupData($schedule, $product->getId(), $dataObject, ['is_in_stock']);

        $newStatus = $schedule->getData(ScheduleInterface::PRODUCT_NEW_STOCK_STATUS);
        $stockItem->setIsInStock($newStatus);
        $this->stockRegistry->updateStockItemBySku($sku, $stockItem);
    }

    /**
     * Revert data from backup
     * @param $schedule
     * @param $product
     */
    public function revert($schedule, $product)
    {
        $backups = $schedule->getBackup();
        $storeId = $schedule->getStoreId();
        if (empty($backups) || !isset($backups[$product->getId()])) {
            return;
        }

        $productData = $backups[$product->getId()];
        if (!isset($productData['is_in_stock'])) {
            return;
        }

        $sku = $product->getSku();
        $stockItem = $this->stockRegistry->getStockItemBySku($sku, $storeId);
        $stockItem->setIsInStock($productData['is_in_stock']);
        $this->stockRegistry->updateStockItemBySku($sku, $stockItem);
    }
}
