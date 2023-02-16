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
 * Actions on price source class
 */
class PriceAction implements \Magento\Framework\Option\ArrayInterface
{
    const MINUS_PERCENTAGE = 'minus_percentage';

    const PLUS_PERCENTAGE = 'plus_percentage';

    const MINUS_FIXED = 'minus_fixed';

    const PLUS_FIXED = 'plus_fixed';

    const REPLACE = 'replace';
    
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
            self::MINUS_PERCENTAGE   => __('Minus percentage from price'),
            self::PLUS_PERCENTAGE   => __('Plus percentage from price'),
            self::MINUS_FIXED   => __('Minus fixed amount'),
            self::PLUS_FIXED   => __('Plus fixed amount'),
            self::REPLACE   => __('Replace'),
        ];

        return $options;
    }
}
