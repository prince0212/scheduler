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

/**
 * Abstract schedule instance class
 */
abstract class AbstractInstance
{
    /**
     * @var \Magetrend\Scheduler\Model\Schedule
     */
    private $schedule;

    /**
     * Returns assigned objets
     * @return []
     */
    abstract public function getAssignedObjects();

    /**
     * Executes after schedule processing, used for save data
     * @return void
     */
    abstract public function afterProcess();

    /**
     * Executes after schedule reverts, used for save data
     * @return void
     */
    abstract public function afterRevert();

    /**
     * Clone schedule instance data
     * @return void
     */
    abstract public function beforeClone();

    /**
     * @param \Magetrend\Scheduler\Model\Schedule $schedule
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * @return \Magetrend\Scheduler\Model\Schedule
     */
    public function getSchedule()
    {
        return $this->schedule;
    }
}
