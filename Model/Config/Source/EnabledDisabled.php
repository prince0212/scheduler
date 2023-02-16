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

/**
 * Enable/Disable options source class
 */
class EnabledDisabled implements \Magento\Framework\Option\ArrayInterface
{
    const DISABLED = 0;

    const ENABLED = 1;

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
        $options = [
            self::DISABLED   => __('Disabled'),
            self::ENABLED   => __('Enabled'),
        ];

        return $options;
    }
}
