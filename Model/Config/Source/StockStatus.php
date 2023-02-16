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
 * Stock status source class
 */
class StockStatus extends \Magento\CatalogInventory\Model\Source\Stock
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
        $data = $this->getAllOptions();
        $options = [];

        foreach ($data as $row) {
            $options[$row['value']] = $row['label'];
        }

        return $options;
    }
}
