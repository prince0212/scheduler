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

/**
 * Schedule managment interface
 */
interface ScheduleManagementInterface
{
    /**
     * Save schedule
     *
     * @param array $data
     * @param int|null $scheduleId
     * @return ScheduleInterface
     */
    public function save($data, $scheduleId = null);
}
