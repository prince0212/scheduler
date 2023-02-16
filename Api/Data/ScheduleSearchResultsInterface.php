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

namespace Magetrend\Scheduler\Api\Data;

/**
 * Interface for schedule search results.
 */
interface ScheduleSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get shedule list.
     *
     * @return \Magetrend\Scheduler\Api\Data\ScheduleInterface[]
     */
    public function getItems();

    /**
     * Set schedule list.
     *
     * @param \Magetrend\Scheduler\Api\Data\ScheduleInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
