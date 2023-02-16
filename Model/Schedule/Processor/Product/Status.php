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

namespace Magetrend\Scheduler\Model\Schedule\Processor\Product;

use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Proessor class: Update product status
 */
class Status extends \Magetrend\Scheduler\Model\Schedule\Processor\Product
{
    /**
     * @var array
     */
    public $attributes = ['status'];

    /**
     * @param ScheduleInterface $schedule
     * @return bool
     */
    public function canProcess($schedule)
    {
        if (!$schedule->getData(ScheduleInterface::PRODUCT_STATUS)) {
            return false;
        }

        return parent::canProcess($schedule);
    }

    /**
     * Update object data
     * @param $schedule
     * @param $product
     */
    public function process($schedule, $product)
    {
        $this->backupData($schedule, $product->getId(), $product, $this->attributes);
        $newStatus = $schedule->getData(ScheduleInterface::PRODUCT_NEW_STATUS);
        $schedule->getInstance()->register('status', $newStatus);
    }
}
