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

namespace Magetrend\Scheduler\Model\Schedule\Processor\CmsBlock;

use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Proessor class: update cms block status
 */
class Status extends \Magetrend\Scheduler\Model\Schedule\Processor\CmsBlock
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
        if (!$schedule->getData(ScheduleInterface::CMS_BLOCK_STATUS)) {
            return false;
        }

        return parent::canProcess($schedule);
    }

    /**
     * Update object data
     * @param $schedule
     * @param $block
     */
    public function process($schedule, $block)
    {
        $this->backupData($schedule, $block->getId(), $block, $this->attributes);
        $newStatus = $schedule->getData(ScheduleInterface::CMS_BLOCK_NEW_STATUS);
        $block->setData('is_active', $newStatus);
    }
}
