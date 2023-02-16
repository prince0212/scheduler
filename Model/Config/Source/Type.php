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
 * Schedule type source class
 */
class Type implements \Magento\Framework\Option\ArrayInterface
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
     *
     * @return array
     */
    public function toArray()
    {
        return [
            ScheduleInterface::TYPE_PRODUCT   => __('Product'),
            ScheduleInterface::TYPE_CATEGORY   => __('Category'),
            ScheduleInterface::TYPE_CMS_PAGE   => __('CMS Page'),
            ScheduleInterface::TYPE_CMS_BLOCK   => __('CMS Block'),
        ];
    }
}
