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

namespace Magetrend\Scheduler\Model;

use Magetrend\Scheduler\Api\Data\ScheduleInterface;
use Magetrend\Scheduler\Api\ScheduleRepositoryInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

/**
 * Schedule repository class
 */
class ScheduleRepository implements ScheduleRepositoryInterface
{
    /**
     * @var array
     */
    private $scheduleRegistry = [];

    /**
     * @var ScheduleInterfaceFactory
     */
    public $scheduleFactory;

    /**
     * @var \Magetrend\Scheduler\Helper\Data
     */
    public $moduleHelper;

    /**
     * @var \Magetrend\Scheduler\Model\ResourceModel\Schedule
     */
    public $scheduleResource;

    /**
     * @var ResourceModel\Schedule\CollectionFactory
     */
    public $collectionFactory;

    /**
     * @var \Magetrend\Scheduler\Api\Data\ScheduleSearchResultsInterfaceFactory
     */
    public $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    public $collectionProcessor;

    /**
     * ScheduleRepository constructor.
     * @param \Magetrend\Scheduler\Api\Data\ScheduleInterfaceFactory $scheduleFactory
     * @param ResourceModel\Schedule $scheduleResource
     * @param \Magetrend\Scheduler\Helper\Data $moduleHelper
     * @param ResourceModel\Schedule\CollectionFactory $collectionFactory
     * @param \Magetrend\Scheduler\Api\Data\ScheduleSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface|null $collectionProcessor
     */
    public function __construct(
        \Magetrend\Scheduler\Api\Data\ScheduleInterfaceFactory $scheduleFactory,
        \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource,
        \Magetrend\Scheduler\Helper\Data $moduleHelper,
        \Magetrend\Scheduler\Model\ResourceModel\Schedule\CollectionFactory $collectionFactory,
        \Magetrend\Scheduler\Api\Data\ScheduleSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->scheduleFactory = $scheduleFactory;
        $this->scheduleResource = $scheduleResource;
        $this->moduleHelper = $moduleHelper;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
    }

    /**
     * Get schedule by id
     * @param $scheduleId
     * @return ScheduleInterface|mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($scheduleId)
    {
        if (isset($this->scheduleRegistry[$scheduleId])) {
            return $this->scheduleRegistry[$scheduleId];
        }

        /**
         * @var ScheduleInterface $entity
         */
        $schedule = $this->scheduleFactory->create()
            ->load($scheduleId);

        if (!$schedule->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('Schedule entity with ID: %1 does not exist:  ', $scheduleId)
            );
        }

        $this->scheduleRegistry[$schedule->getId()] = $schedule;
        return $schedule;
    }

    /**
     * Returns new schedule empty object
     * @return ScheduleInterface
     */
    public function getNew()
    {
        return $this->scheduleFactory->create();
    }

    /**
     * Save schedule object
     * @param ScheduleInterface $schedule
     * @return  ScheduleInterface
     */
    public function save(ScheduleInterface $schedule)
    {
        $this->scheduleResource->save($schedule);
        return $schedule;
    }

    /**
     * Returns schedule collection
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Magetrend\Scheduler\Api\Data\ScheduleSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var \Magetrend\Scheduler\Model\ResourceModel\Schedule\Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var \Magetrend\Scheduler\Api\Data\ScheduleSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Retrieve collection processor
     *
     * @return CollectionProcessorInterface
     */
    private function getCollectionProcessor()
    {
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'Magetrend\Scheduler\Model\Api\SearchCriteria\ScheduleCollectionProcessor'
            );
        }
        return $this->collectionProcessor;
    }

    /**
     * Delete schedule by id
     * @param $scheduleId
     */
    public function delete($scheduleId)
    {
        $schedule = $this->get($scheduleId);
        $schedule->delete();
    }
}
