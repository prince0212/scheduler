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
 * Category schedule instance class
 */
class CategoryInstance extends \Magetrend\Scheduler\Model\Schedule\AbstractInstance
{
    /**
     * @var array
     */
    private $assignedObjects = [];

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    public $categoryRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    public $searchCriteriaBuilder;

    /**
     * @var \Magetrend\Scheduler\Model\ResourceModel\Schedule
     */
    public $scheduleResource;

    /**
     * @var array
     */
    public $registry = [];

    /**
     * CategoryInstance constructor.
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->scheduleResource = $scheduleResource;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Returns assigned category ids
     * @return array
     */
    public function getCategoryIds()
    {
        return $this->scheduleResource->getCategoryByScheduleId($this->getSchedule()->getId());
    }

    /**
     * Returns assigned object array
     * @return array
     */
    public function getAssignedObjects()
    {
        $schedule = $this->getSchedule();
        $storeId = $schedule->getStoreId();
        if (!isset($this->assignedObjects[$storeId])) {
            $categoryIds = $this->getCategoryIds();
            if (empty($categoryIds)) {
                $this->assignedObjects[$storeId] = [];
            }

            foreach ($categoryIds as $categoryId) {
                try {
                    $this->assignedObjects[$storeId][] = $this->categoryRepository->get($categoryId, $storeId);
                } catch (NoSuchEntityException $e) {
                    continue;
                }
            }
        }

        return $this->assignedObjects[$storeId];
    }

    /**
     * Register data for backup
     * @param $attribute
     * @param $value
     */
    public function register($attribute, $value)
    {
        $storeId = $this->getSchedule()->getStoreId();
        $this->registry[$storeId][$attribute] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function afterProcess()
    {
        $this->saveAssignedObjects();
    }

    /**
     * {@inheritdoc}
     */
    public function afterRevert()
    {
        $this->saveAssignedObjects();
    }

    /**
     * Save categories after execute all processors
     */
    public function saveAssignedObjects()
    {
        $assignedObjects = $this->getAssignedObjects();
        if (empty($assignedObjects)) {
            return;
        }

        foreach ($assignedObjects as $category) {
            $category->save();
        }
    }

    /**
     * Clone categories ids
     */
    public function beforeClone()
    {
        $this->getSchedule()->setData(ScheduleInterface::CATEGORY_IDS, $this->getCategoryIds());
    }
}
