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

namespace Magetrend\Scheduler\Model\Schedule\Processor\Category;

use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Proessor class: Update category status
 */
class Status extends \Magetrend\Scheduler\Model\Schedule\Processor\Category
{
    /**
     * @var array
     */
    public $attributes = ['is_active'];

    /**
     * @param ScheduleInterface $schedule
     * @return bool
     */
    public function canProcess($schedule)
    {
        if (!$schedule->getData(ScheduleInterface::CATEGORY_STATUS)) {
            return false;
        }

        return parent::canProcess($schedule);
    }

    /**
     * Update object data
     * @param $schedule
     * @param $category
     */
    public function process($schedule, $category)
    {
        $this->backupData($schedule, $category->getId(), $category, $this->attributes);
        $newStatus = $schedule->getData(ScheduleInterface::CATEGORY_NEW_STATUS);
        $category->setData('is_active', $newStatus);
    }
}
