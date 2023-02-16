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
 * Clock element block class
 */
class Clock extends \Magento\Framework\Data\Form\Element\Label
{

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    public $timezone;

    /**
     * Clock constructor.
     * @param Factory $factoryElement
     * @param CollectionFactory $factoryCollection
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param Escaper $escaper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Escaper $escaper,
        $data = []
    ) {
        $this->timezone = $timezone;
        $this->date = $date;
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
    }

    /**
     * Retrieve Element HTML
     *
     * @return string
     */
    public function getElementHtml()
    {
        $note = '';
        $value = $this->timezone->formatDateTime(
            $this->timezone->date(),
            \IntlDateFormatter::SHORT,
            \IntlDateFormatter::LONG
        );

        $html = '<div class="control-value">'.$value . '<br/><div class="note admin__field-note">'.$note.'</div></div>';
        $html .= $this->getAfterElementHtml();
        return $html;
    }
}
