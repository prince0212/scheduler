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

use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Schedule edit form container class
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    public $coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize blog post edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Magetrend_Scheduler';
        $this->_controller = 'adminhtml_schedule';
        $schedule = $this->coreRegistry->registry('current_model');

        parent::_construct();

        if ($schedule->getId()) {
            $this->addButton(
                'clone',
                [
                    'label' => __('Clone'),
                    'onclick' => 'setLocation(\'' . $this->getCloneUrl() . '\')',
                    'class' => 'clone'
                ],
                -1
            );
        }

        if ($schedule->getId() && $schedule->getIsActive()
            && in_array($schedule->getStatus(), [ScheduleInterface::STATUS_SCHEDULED])) {
            $this->addButton(
                'process',
                [
                    'label' => __('Run Now'),
                    'onclick' => 'setLocation(\'' . $this->getProcessUrl() . '\')',
                    'class' => 'process'
                ],
                -1
            );
        }

        if ($schedule->getId() && $schedule->getIsActive()
            && in_array($schedule->getStatus(), [ScheduleInterface::STATUS_RUNNING])) {
            $this->addButton(
                'revert',
                [
                    'label' => __('Revert Data'),
                    'onclick' => 'setLocation(\'' . $this->getRevertUrl() . '\')',
                    'class' => 'revert'
                ],
                -1
            );
        }

        $this->buttonList->update('save', 'label', __('Save Schedule'));
        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ],
            -100
        );
    }

    /**
     * Returns url to clone schedule
     * @return string
     */
    public function getCloneUrl()
    {
        return $this->getUrl('scheduler/*/duplicate', [
            'id' => $this->coreRegistry->registry('current_model')->getId()
        ]);
    }

    /**
     * Returns url to process schedule
     * @return string
     */
    public function getProcessUrl()
    {
        return $this->getUrl('scheduler/*/process', [
            'id' => $this->coreRegistry->registry('current_model')->getId()
        ]);
    }

    /**
     * Returns url to revert schedule
     * @return string
     */
    public function getRevertUrl()
    {
        return $this->getUrl('scheduler/*/revert', [
            'id' => $this->coreRegistry->registry('current_model')->getId()
        ]);
    }
}
