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
 * Cms page schedule instance class
 */
class CmsPageInstance extends \Magetrend\Scheduler\Model\Schedule\AbstractInstance
{
    /**
     * @var array
     */
    private $assignedObjects = [];

    /**
     * @var \Magento\Cms\Api\PageRepositoryInterface
     */
    public $pageRepository;

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
     * CmsPageInstance constructor.
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource
     * @param \Magento\Cms\Api\PageRepositoryInterface $pageRepository
     */
    public function __construct(
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource,
        \Magento\Cms\Api\PageRepositoryInterface $pageRepository
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->scheduleResource = $scheduleResource;
        $this->pageRepository = $pageRepository;
    }

    /**
     * Returns assgined cms page ids
     * @return array
     */
    public function getCmsPageIds()
    {
        return $this->scheduleResource->getCmsPageByScheduleId($this->getSchedule()->getId());
    }

    /**
     * Returns assigned cms pages
     * @return array
     */
    public function getAssignedObjects()
    {
        $schedule = $this->getSchedule();
        $storeId = $schedule->getStoreId();
        if (!isset($this->assignedObjects[$storeId])) {
            $cmsPageIds = $this->getCmsPageIds();
            if (empty($cmsPageIds)) {
                $this->assignedObjects[$storeId] = [];
            }

            foreach ($cmsPageIds as $pageId) {
                try {
                    $this->assignedObjects[$storeId][] = $this->pageRepository->getById($pageId);
                } catch (NoSuchEntityException $e) {
                    continue;
                }
            }
        }

        return $this->assignedObjects[$storeId];
    }

    /**
     * Collect data for save
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
     * Save assigned object collection
     */
    public function saveAssignedObjects()
    {
        $assignedObjects = $this->getAssignedObjects();
        if (empty($assignedObjects)) {
            return;
        }

        foreach ($assignedObjects as $page) {
            $this->pageRepository->save($page);
        }
    }

    /**
     * Clone cms page ids
     */
    public function beforeClone()
    {
        $this->getSchedule()->setData(ScheduleInterface::CMS_PAGE_IDS, $this->getCmsPageIds());
    }
}
