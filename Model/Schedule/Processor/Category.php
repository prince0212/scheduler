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

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magetrend\Scheduler\Api\Data\ScheduleInterface;
use Magetrend\Scheduler\Model\Schedule;

/**
 * Abstract category process class
 */
abstract class Category extends \Magetrend\Scheduler\Model\Schedule\Processor\AbstractProcessor
{
    /**
     * @var array
     */
    public $attributes = [];

    /**
     * Returns list of attribute to save in backup
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Returns processor type
     * @return int
     */
    public function getType()
    {
        return ScheduleInterface::TYPE_CATEGORY;
    }

    /**
     * Revert object data from backup
     * @param $schedule
     * @param $category
     */
    public function revert($schedule, $category)
    {
        $backups = $schedule->getBackup();
        $storeId = $schedule->getStoreId();
        if (empty($backups) || !isset($backups[$category->getId()])) {
            return;
        }

        $categoryData = $backups[$category->getId()];
        foreach ($this->getAttributes() as $attributeCode) {
            if (!isset($categoryData[$attributeCode])) {
                continue;
            }

            $category->setData($attributeCode, $categoryData[$attributeCode]);
        }
    }
}
