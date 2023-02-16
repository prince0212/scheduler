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
 * Cms block schedule instance class
 */
class CmsBlockInstance extends \Magetrend\Scheduler\Model\Schedule\AbstractInstance
{
    /**
     * @var array
     */
    private $assignedObjects = [];

    /**
     * @var \Magento\Cms\Api\BlockRepositoryInterface
     */
    public $blockRepository;

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
     * CmsBlockInstance constructor.
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource
     * @param \Magento\Cms\Api\BlockRepositoryInterface $blockRepository
     */
    public function __construct(
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource,
        \Magento\Cms\Api\BlockRepositoryInterface $blockRepository
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->scheduleResource = $scheduleResource;
        $this->blockRepository = $blockRepository;
    }

    /**
     * Returns assigned cms block ids
     * @return array
     */
    public function getCmsBlockIds()
    {
        return $this->scheduleResource->getCmsBlockByScheduleId($this->getSchedule()->getId());
    }

    /**
     * Returns assigned cms block objects
     * @return array
     */
    public function getAssignedObjects()
    {
        $schedule = $this->getSchedule();
        $storeId = $schedule->getStoreId();
        if (!isset($this->assignedObjects[$storeId])) {
            $cmsPageIds = $this->getCmsBlockIds();
            if (empty($cmsPageIds)) {
                $this->assignedObjects[$storeId] = [];
            }

            foreach ($cmsPageIds as $blockId) {
                try {
                    $this->assignedObjects[$storeId][] = $this->blockRepository->getById($blockId);
                } catch (NoSuchEntityException $e) {
                    continue;
                }
            }
        }

        return $this->assignedObjects[$storeId];
    }

    /**
     * Register data for mass save
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
     * Save assigned and update objects
     */
    public function saveAssignedObjects()
    {
        $assignedObjects = $this->getAssignedObjects();
        if (empty($assignedObjects)) {
            return;
        }

        foreach ($assignedObjects as $block) {
            $this->blockRepository->save($block);
        }
    }

    /**
     * Clone cms block ids
     */
    public function beforeClone()
    {
        $this->getSchedule()->setData(ScheduleInterface::CMS_BLOCK_IDS, $this->getCmsBlockIds());
    }
}
