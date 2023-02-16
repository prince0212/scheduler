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

namespace Magetrend\Scheduler\Cron\Schedule;

use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Revert schedule cron class
 */
class Revert
{
    /**
     * @var \Magetrend\Scheduler\Api\ScheduleRepositoryInterface
     */
    public $scheduleRepository;

    /**
     * @var \Magetrend\Scheduler\Model\ScheduleProcessor
     */
    public $scheduleProcessor;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    public $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    public $date;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    public $timezone;

    /**
     * Revert constructor.
     * @param \Magetrend\Scheduler\Api\ScheduleRepositoryInterface $scheduleRepository
     * @param \Magetrend\Scheduler\Model\ScheduleProcessor $scheduleProcessor
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     */
    public function __construct(
        \Magetrend\Scheduler\Api\ScheduleRepositoryInterface $scheduleRepository,
        \Magetrend\Scheduler\Model\ScheduleProcessor $scheduleProcessor,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
    ) {
        $this->scheduleRepository = $scheduleRepository;
        $this->scheduleProcessor = $scheduleProcessor;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->date = $date;
        $this->timezone = $timezone;
    }

    /**
     * Execute task
     */
    public function execute()
    {
        $currentTime = $this->timezone->date()->format('Y-m-d H:i:s');
        $searchScriteria = $this->searchCriteriaBuilder
            ->addFilter(ScheduleInterface::IS_ACTIVE, 1)
            ->addFilter(ScheduleInterface::DATE_TO, $this->date->gmtDate(), 'lteq')
            ->addFilter(ScheduleInterface::STATUS, ScheduleInterface::STATUS_RUNNING);

        $collection = $this->scheduleRepository->getList($searchScriteria->create());

        if ($collection->getTotalCount() == 0) {
            return;
        }

        foreach ($collection->getItems() as $schedule) {
            $schedule->revert();
        }
    }
}
