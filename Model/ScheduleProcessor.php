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

/**
 * Schedule processing class
 */
class ScheduleProcessor
{
    /**
     * @var array
     */
    private $processor = [
        \Magetrend\Scheduler\Model\Schedule\Processor\Product\SpecialPrice::class,
        \Magetrend\Scheduler\Model\Schedule\Processor\Product\Status::class,
        \Magetrend\Scheduler\Model\Schedule\Processor\Product\StockStatus::class,
        \Magetrend\Scheduler\Model\Schedule\Processor\Product\Category::class,
        \Magetrend\Scheduler\Model\Schedule\Processor\CmsPage\Status::class,
        \Magetrend\Scheduler\Model\Schedule\Processor\CmsBlock\Status::class,
        \Magetrend\Scheduler\Model\Schedule\Processor\Category\Status::class,
    ];

    /**
     * Processors objects
     * @var null
     */
    private $processorList = null;

    /**
     * @var \Magetrend\Scheduler\Helper\Data
     */
    public $moduleHelper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    public $date;

    /**
     * @var ScheduleRepositoryInterface
     */
    public $scheduleRepository;

    /**
     * @var ResourceModel\Schedule
     */
    public $scheduleResource;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    public $timezone;

    /**
     * ScheduleProcessor constructor.
     * @param \Magetrend\Scheduler\Helper\Data $moduleHelper
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param ScheduleRepositoryInterface $scheduleRepository
     * @param ResourceModel\Schedule $scheduleResource
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     */
    public function __construct(
        \Magetrend\Scheduler\Helper\Data $moduleHelper,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        ScheduleRepositoryInterface $scheduleRepository,
        \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
    ) {
        $this->moduleHelper = $moduleHelper;
        $this->date = $date;
        $this->scheduleRepository = $scheduleRepository;
        $this->scheduleResource = $scheduleResource;
        $this->timezone = $timezone;
    }

    /**
     * Process schedule
     * @param ScheduleInterface $schedule
     */
    public function process($schedule)
    {
        $assignedObjets = $schedule->getInstance()->getAssignedObjects();
        if (!empty($assignedObjets)) {
            $processerors = $this->getProcessors();
            foreach ($assignedObjets as $object) {
                foreach ($processerors as $processor) {
                    if ($processor->canProcess($schedule)) {
                        $processor->process($schedule, $object);
                    }
                }
            }

            $schedule->getInstance()->afterProcess();
            $this->scheduleResource->saveBackup($schedule);
        }
    }

    /**
     * Revert data
     * @param ScheduleInterface $schedule
     */
    public function revert($schedule)
    {
        $assignedObjets = $schedule->getInstance()->getAssignedObjects();
        if (!empty($assignedObjets)) {
            $processerors = $this->getProcessors();
            foreach ($assignedObjets as $object) {
                foreach ($processerors as $processor) {
                    if ($processor->canRevert($schedule)) {
                        $processor->revert($schedule, $object);
                    }
                }
            }
            $schedule->getInstance()->afterRevert();
        }
    }

    /**
     * Returns processors instances
     * @return \Magetrend\Scheduler\Model\Schedule\Processor\aAbstractProcessor[]
     */
    public function getProcessors()
    {
        if ($this->processorList == null) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            foreach ($this->processor as $processor) {
                $this->processorList[] = $objectManager->get($processor);
            }
        }

        return $this->processorList;
    }
}
