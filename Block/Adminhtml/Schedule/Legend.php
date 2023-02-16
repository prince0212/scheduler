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

namespace Magetrend\Scheduler\Block\Adminhtml\Schedule;

/**
 * Schedule legend block class
 */
class Legend extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magetrend\Scheduler\Model\Config\Source\ScheduleStatus
     */
    public $status;

    /**
     * Legend constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magetrend\Scheduler\Model\Config\Source\ScheduleStatus $status
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magetrend\Scheduler\Model\Config\Source\ScheduleStatus $status,
        array $data = []
    ) {
        $this->status = $status;
        parent::__construct($context, $data);
    }

    /**
     * Returns list of schedule statuses
     * @return array
     */
    public function getStatuses()
    {
        return $this->status->getConfig();
    }
}
