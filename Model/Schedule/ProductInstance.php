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

namespace Magetrend\Scheduler\Model\Schedule;

use Magento\Framework\Exception\NoSuchEntityException;
use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Product schedule instance class
 */
class ProductInstance extends \Magetrend\Scheduler\Model\Schedule\AbstractInstance
{
    /**
     * @var array
     */
    private $assignedObjects = [];

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    public $searchCriteriaBuilder;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    public $productRepository;

    /**
     * @var array
     */
    public $registry = [];

    /**
     * @var array
     */
    public $categoryRegistry = [];

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Action
     */
    public $action;

    /**
     * @var \Magetrend\Scheduler\Model\ResourceModel\Schedule
     */
    public $scheduleResource;

    public $categoryRepository;

    /**
     * ProductInstance constructor.
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Catalog\Model\ResourceModel\Product\Action $action
     * @param \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\ResourceModel\Product\Action $action,
        \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->productRepository = $productRepository;
        $this->action = $action;
        $this->scheduleResource = $scheduleResource;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Returns assigned products ids
     * @return array
     */
    public function getProducts()
    {
        return $this->scheduleResource->getProductsByScheduleId($this->getSchedule()->getId());
    }

    /**
     * Returns assigned product list
     * @return array
     */
    public function getAssignedObjects()
    {
        $schedule = $this->getSchedule();
        $storeId = $schedule->getStoreId();
        if (!isset($this->assignedObjects[$storeId])) {
            $productIdsByCondition = $schedule->getProductsByConditions();
            if (!empty($productIdsByCondition)) {
                $productIdsByCondition = array_unique($productIdsByCondition);
            }
            $productIds = array_merge($this->getProducts(), $productIdsByCondition);

            if (empty($productIds)) {
                $this->assignedObjects[$storeId] = [];
            }

            foreach ($productIds as $productId) {
                try {
                    $product = $this->productRepository->getById($productId, true, $storeId);
                    $this->assignedObjects[$storeId][] = $product;
                } catch (NoSuchEntityException $e) {
                    continue;
                }
            }
        }

        return $this->assignedObjects[$storeId];
    }

    /**
     * Collect data for mass update
     * @param $attribute
     * @param $value
     */
    public function register($attribute, $value)
    {
        $storeId = $this->getSchedule()->getStoreId();
        $this->registry[$storeId][$attribute] = $value;
    }

    /**
     * Collect data for mass category update
     * @param $productId
     * @param $categortIds
     * @param $remove
     */
    public function registerCategory($productId, $categortIds, $remove = false)
    {
        if (empty($categortIds)) {
            return;
        }

        $storeId = $this->getSchedule()->getStoreId();
        $key = $remove?'remove':'add';

        foreach ($categortIds as $categortId) {
            $this->categoryRegistry[$storeId][$categortId][$key][] = $productId;
        }
    }

    /**
     * Mass product update
     */
    public function updateFromRegistry()
    {
        $assignedObjects = $this->getAssignedObjects();
        $storeId = $this->getSchedule()->getStoreId();

        if (empty($assignedObjects) || !isset($this->registry[$storeId])) {
            return;
        }

        $productIds = [];
        if (!empty($this->registry[$storeId])) {
            $data = $this->registry[$storeId];
            foreach ($assignedObjects as $product) {
               $product->addData($data);
               $this->productRepository->save($product);
            }
        }
    }

    /**
     * Mass product update
     */
    public function updateCategoryFromRegistry()
    {
        $storeId = $this->getSchedule()->getStoreId();
        if (empty($this->categoryRegistry) || !isset($this->categoryRegistry[$storeId])) {
            return;
        }

        foreach ($this->categoryRegistry[$storeId] as $categoryId => $updates) {
            $category = $this->categoryRepository->get($categoryId, $storeId);
            $products = $category->getProductsPosition();
            if (isset($updates['add'])) {
                $products = $this->addCategoryProducts($products, $updates['add']);
            }

            if (isset($updates['remove'])) {
                $products = $this->removeCategoryProducts($products, $updates['remove']);
            }

            $category->setPostedProducts($products);
            $category->save();
        }
    }

    /**
     * @param array $products
     * @param array $prodcutIds
     * @return array
     */
    public function addCategoryProducts($products, $prodcutIds)
    {
        if (empty($prodcutIds)) {
            return $products;
        }
        foreach ($prodcutIds as $prodcutId) {
            if (!isset($products[$prodcutId])) {
                $products[$prodcutId] = 0;
            }
        }

        return $products;
    }

    /**
     * @param array $products
     * @param array $prodcutIds
     * @return array
     */
    public function removeCategoryProducts($products, $prodcutIds)
    {
        if (empty($prodcutIds)) {
            return $products;
        }

        foreach ($prodcutIds as $prodcutId) {
            if (isset($products[$prodcutId])) {
                unset($products[$prodcutId]);
            }
        }

        return $products;
    }

    /**
     * {@inheritdoc}
     */
    public function afterProcess()
    {
        $this->updateFromRegistry();
        $this->updateCategoryFromRegistry();
    }

    /**
     * {@inheritdoc}
     */
    public function afterRevert()
    {
        $this->updateCategoryFromRegistry();
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeClone()
    {
        $this->getSchedule()->setData(ScheduleInterface::PRODUCT_IDS, $this->getProducts());
    }
}
