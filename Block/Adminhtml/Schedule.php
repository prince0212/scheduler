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

namespace Magetrend\Scheduler\Block\Adminhtml;

use Magento\Core\Model\ObjectManager;

/**
 * Schedule grid containter block class
 */
class Schedule extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @var \Magetrend\Scheduler\Model\Config\Source\Type
     */
    public $contentType;

    /**
     * Schedule constructor.
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magetrend\Scheduler\Model\Config\Source\Type $contentType
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magetrend\Scheduler\Model\Config\Source\Type $contentType,
        array $data = []
    ) {
        $this->contentType = $contentType;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'schedule_index';
        $this->_headerText = __('Manage Newsletter Schedule');
        parent::_construct();
    }

    /**
     * Prepare container layout
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->removeButton('add');
        $this->addButton('add', [
            'id' => 'add_new_item',
            'label' => '&nbsp;'.__('New Schedule').'&nbsp;&nbsp;&nbsp;&nbsp;',
            'class' => 'add',
            'button_class' => '',
            'class_name' => 'Magento\Backend\Block\Widget\Button\SplitButton',
            'options' => $this->getAddButtonOptions(),
        ]);
        return parent::_prepareLayout();
    }

    /**
     * Add button with options
     * @return array
     */
    public function getAddButtonOptions()
    {
        $splitButtonOptions = [];
        $options = $this->contentType->toArray();
        foreach ($options as $key => $value) {
            $splitButtonOptions[] = [
                'label' => __($value),
                'onclick' => "setLocation('" . $this->getCreateNewUrl($key) . "')"
            ];
        }

        return $splitButtonOptions;
    }

    /**
     * Returns url to create new schedule
     * @param $key
     * @return string
     */
    public function getCreateNewUrl($key)
    {
        return $this->getUrl(
            '*/*/new',
            ['type' => $key]
        );
    }
}
