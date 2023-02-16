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

namespace Magetrend\Scheduler\Block\Adminhtml\Form\Element;

use Magento\Framework\Data\Form\Element\CollectionFactory;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Escaper;
use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Status element block class
 */
class Status extends \Magento\Framework\Data\Form\Element\Label
{
    /**
     * @var \Magetrend\Scheduler\Model\Config\Source\ScheduleStatus
     */
    public $scheduleStatus;

    /**
     * Status constructor.
     * @param Factory $factoryElement
     * @param CollectionFactory $factoryCollection
     * @param Escaper $escaper
     * @param \Magetrend\Scheduler\Model\Config\Source\ScheduleStatus $scheduleStatus
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Framework\Escaper $escaper,
        \Magetrend\Scheduler\Model\Config\Source\ScheduleStatus $scheduleStatus,
        $data = []
    ) {
        $this->scheduleStatus = $scheduleStatus;
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
    }

    /**
     * Retrieve Element HTML
     *
     * @return string
     */
    public function getElementHtml()
    {
        $options = $this->scheduleStatus->toArray();
        $status = $this->getValue();

        if (isset($options[$status])) {
            $value = '<span class="schedule-status schedule-status-'.$status.'">'.$options[$status].'</span>';
        }

        $note = '';
        switch ($status) {
            case ScheduleInterface::STATUS_FINISHED:
                $note = __('The schedule is finished. If you want to schedule this task again - clone it.');
        }

        $html = '<div class="control-value">'.$value . '<br/><div class="note admin__field-note">'.$note.'</div></div>';
        $html .= $this->getAfterElementHtml();
        return $html;
    }
}
