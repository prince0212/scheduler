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

use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Abstract process class
 */
abstract class AbstractProcessor
{
    /**
     * @var \Magetrend\Scheduler\Model\ResourceModel\Schedule
     */
    public $scheduleResource;

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * Update data in object
     * @param $schedule
     * @param $object
     */
    abstract public function process($schedule, $object);

    /**
     * Revert data in ibject
     * @param $schedule
     * @param $object
     */
    abstract public function revert($schedule, $object);

    /**
     * AbstractProcessor constructor.
     * @param \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource
     */
    public function __construct(
        \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource
    ) {
        $this->scheduleResource = $scheduleResource;
    }

    /**
     * @param ScheduleInterface $schedule
     * @return bool
     */
    public function canProcess($schedule)
    {
        if ($schedule->getType() != $this->getType()) {
            return false;
        }

        return true;
    }

    /**
     * @param ScheduleInterface $schedule
     * @return bool
     */
    public function canRevert($schedule)
    {
        if ($schedule->getType() != $this->getType()) {
            return false;
        }

        return true;
    }

    /**
     * Collecting backup data
     * @param $schedule
     * @param $relatedObjectId
     * @param $dataObject
     * @param array $attributeList
     * @return bool
     */
    public function backupData($schedule, $relatedObjectId, $dataObject, $attributeList = [])
    {
        if (empty($attributeList)) {
            return true;
        }

        $backup = $schedule->getBackup();
        foreach ($attributeList as $attributeCode) {
            $value = $dataObject->getData($attributeCode);
            $backup[$relatedObjectId][$attributeCode] = $value;
        }

        $schedule->setBackup($backup);
    }
}
