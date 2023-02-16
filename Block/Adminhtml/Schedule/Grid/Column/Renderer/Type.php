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

/**
 * Schedule type column rednderer class
 */
class Type extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    /**
     * @var \Magetrend\Scheduler\Model\Config\Source\Type
     */
    public $type;

    /**
     * Type constructor.
     * @param \Magento\Backend\Block\Context $context
     * @param \Magetrend\Scheduler\Model\Config\Source\Type $type
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magetrend\Scheduler\Model\Config\Source\Type $type,
        array $data = []
    ) {
        $this->type = $type;
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
        $config = $this->type->toArray();
        if (isset($config[$value])) {
            $value = $config[$value];
        }

        return $value;
    }
}
