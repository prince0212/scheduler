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

namespace Magetrend\Scheduler\Model\Schedule\Processor\CmsPage;

use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Proessor class: Update cms page status
 */
class Status extends \Magetrend\Scheduler\Model\Schedule\Processor\CmsPage
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
        if (!$schedule->getData(ScheduleInterface::CMS_PAGE_STATUS)) {
            return false;
        }

        return parent::canProcess($schedule);
    }

    /**
     * Update object data
     * @param $schedule
     * @param $page
     */
    public function process($schedule, $page)
    {
        $this->backupData($schedule, $page->getId(), $page, $this->attributes);
        $newStatus = $schedule->getData(ScheduleInterface::CMS_PAGE_NEW_STATUS);
        $page->setData('is_active', $newStatus);
    }
}
