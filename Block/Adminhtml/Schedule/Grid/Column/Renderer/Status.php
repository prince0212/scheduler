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

namespace Magetrend\Scheduler\Block\Adminhtml\Schedule\Grid\Column\Renderer;

use Magento\Framework\DataObject;
use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Schedule status column rednderer class
 */
class Status extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    /**
     * @var \Magetrend\Scheduler\Model\Config\Source\ScheduleStatus
     */
    public $status;

    /**
     * Status constructor.
     * @param \Magento\Backend\Block\Context $context
     * @param \Magetrend\Scheduler\Model\Config\Source\ScheduleStatus $status
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magetrend\Scheduler\Model\Config\Source\ScheduleStatus $status,
        array $data = []
    ) {
        $this->status = $status;
        parent::__construct($context, $data);
    }

    /**
     * Render column value
     * @param DataObject $row
     * @return string
     */
    public function render(DataObject $row)
    {
        $index = $this->getColumn()->getIndex();
        $value = $row->getData($index);
        $label = $value;
        if ($row->getData('is_active') == 0) {
            $value = ScheduleInterface::STATUS_PAUSED;
        }

        $config = $this->status->toArray();
        if (isset($config[$value])) {
            $label = $config[$value];
        }

        return '<span class="schedule-status schedule-status-'.$value.'">'.$label.'</span>';
    }
}
