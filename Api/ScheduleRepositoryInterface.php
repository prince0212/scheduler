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

namespace Magetrend\Scheduler\Api;

use Magetrend\Scheduler\Api\Data\ScheduleInterface;
use Magetrend\Scheduler\Api\Data\ScheduleSearchResultsInterface;

/**
 * Schedule repository interface
 */
interface ScheduleRepositoryInterface
{
    /**
     * Get entity object
     *
     * @param $scheduleId
     * @return ScheduleInterface
     */
    public function get($scheduleId);

    /**
     * Returns new instance of object
     *
     * @return ScheduleInterface
     */
    public function getNew();

    /**
     * Save schedule object
     * @param ScheduleInterface $schedule
     * @return ScheduleInterface
     */
    public function save(ScheduleInterface $schedule);

    /**
     * Delete schedule
     * @param $scheduleId
     * @return void
     */
    public function delete($scheduleId);

    /**
     * Returns list of schedules
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return ScheduleSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
