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
 * Schedule mass schedule action source class
 */
class MassAction implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'name' => 'disable',
                'label' => __('Disable'),
                'url' => '*/*/massDisable'
            ],[
                'name' => 'enable',
                'label' => __('Enable'),
                'url' => '*/*/massEnable'
            ],[
                'name' => 'delete',
                'label' => __('Delete'),
                'url' => '*/*/massDelete'
            ],
        ];
    }
}