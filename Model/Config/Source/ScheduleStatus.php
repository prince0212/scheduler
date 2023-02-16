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

namespace Magetrend\Scheduler\Model\Config\Source;

use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Schedule status source class
 */
class ScheduleStatus implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $data = $this->toArray();
        $optionArray = [];
        foreach ($data as $value => $label) {
            $optionArray[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $optionArray;
    }

    /**
     * Get options in "key-value" format
     * @return array
     */
    public function toArray()
    {
        $statuses = [];
        foreach ($this->getConfig() as $status) {
            $statuses[$status['value']] = $status['label'];
        }
        return $statuses;
    }

    /**
     * Returns statuses
     * @return array
     */
    public function getConfig()
    {
        $config = [
            [
                'value' => ScheduleInterface::STATUS_SCHEDULED,
                'label' => __('Scheduled'),
                'tooltip' => __('A schedule has been created and is awaiting its "Start Date" to process data.'),
            ],[
                'value' => ScheduleInterface::STATUS_RUNNING,
                'label' => __('Running'),
                'tooltip' => __(
                    'The schedule has been processed and is awaiting its "End Date" to revert to previous data.'
                ),
            ],[
                'value' => ScheduleInterface::STATUS_FINISHED,
                'label' => __('Finished'),
                'tooltip' => __('A schedule was processed successfully and has been completed.'),
            ],[
                'value' => ScheduleInterface::STATUS_MISSED,
                'label' => __('Missed'),
                'tooltip' => __('A schedule failed to process.'),
            ],[
                'value' => ScheduleInterface::STATUS_PAUSED,
                'label' => __('Paused'),
                'tooltip' => __('A schedule has been disabled and will not be processed.'),
            ],
        ];

        return $config;
    }
}
