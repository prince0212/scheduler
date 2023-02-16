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

namespace Magetrend\Scheduler\Model\Schedule\Processor;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magetrend\Scheduler\Api\Data\ScheduleInterface;
use Magetrend\Scheduler\Model\Schedule;

/**
 * Abstract product process class
 */
abstract class Product extends \Magetrend\Scheduler\Model\Schedule\Processor\AbstractProcessor
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    public $productRepository;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Action
     */
    public $action;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    public $productResource;

    /**
     * @var array
     */
    public $attributes = [];

    /**
     * Product constructor.
     * @param \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Catalog\Model\ResourceModel\Product\Action $action
     * @param \Magento\Catalog\Model\ResourceModel\Product $productResource
     */
    public function __construct(
        \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\ResourceModel\Product\Action $action,
        \Magento\Catalog\Model\ResourceModel\Product $productResource
    ) {
        $this->productRepository = $productRepository;
        $this->action = $action;
        $this->productResource = $productResource;
        parent::__construct($scheduleResource);
    }

    /**
     * Returns list of attribute to save in backup
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Returns processor type
     * @return int
     */
    public function getType()
    {
        return ScheduleInterface::TYPE_PRODUCT;
    }

    /**
     * Revert data from backup
     * @param $schedule
     * @param $product
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function revert($schedule, $product)
    {
        $backups = $schedule->getBackup();
        $storeId = $schedule->getStoreId();
        if (empty($backups) || !isset($backups[$product->getId()])) {
            return;
        }

        $productData = $backups[$product->getId()];
        foreach ($this->getAttributes() as $attributeCode) {
            if (!isset($productData[$attributeCode])) {
                continue;
            }

            if ($product->getData($attributeCode) == $productData[$attributeCode]) {
                /**
                 * Nothing to change
                 */
                continue;
            }

            $product->setData($attributeCode, $productData[$attributeCode]);
        }

        $this->productRepository->save($product);
    }
}
