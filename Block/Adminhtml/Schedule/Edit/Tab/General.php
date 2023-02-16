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

namespace Magetrend\Scheduler\Block\Adminhtml\Schedule\Edit\Tab;

use \Magento\Backend\Block\Widget\Form\Generic;
use \Magento\Backend\Block\Widget\Tab\TabInterface;
use Magetrend\GiftCard\Model\Config\Source\Status;
use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * General schedule tab block class
 */
class General extends Generic implements TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    public $store;

    /**
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    public $yesNo;

    /**
     * @var \Magetrend\Scheduler\Model\Config\Source\ScheduleStatus
     */
    public $scheduleStatus;

    /**
     * General constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magento\Config\Model\Config\Source\Yesno $yesno
     * @param \Magetrend\Scheduler\Model\Config\Source\ScheduleStatus $scheduleStatus
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Config\Model\Config\Source\Yesno $yesno,
        \Magetrend\Scheduler\Model\Config\Source\ScheduleStatus $scheduleStatus,
        array $data = []
    ) {
        $this->store = $systemStore;
        $this->yesNo = $yesno;
        $this->scheduleStatus = $scheduleStatus;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_model');
        $type = $this->_request->getParam('type')?$this->_request->getParam('type'):$model->getType();

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('page_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('General Settings')]);

        $fieldset->addField(
            'schedule_id',
            'hidden',
            [
                'name' => 'schedule_id',
                'value' => ''
            ]
        );

        $fieldset->addField(
            ScheduleInterface::TYPE,
            'hidden',
            [
                'name' => 'schedule['.ScheduleInterface::TYPE.']',
                'value' => ''
            ]
        );

        $dateFormat = $this->_localeDate->getDateFormat(
            \IntlDateFormatter::SHORT
        );

        $timeFormat = $this->_localeDate->getTimeFormat(
            \IntlDateFormatter::MEDIUM
        );

        $fieldset->addField(
            ScheduleInterface::IS_ACTIVE,
            'select',
            [
                'name' => 'schedule['.ScheduleInterface::IS_ACTIVE.']',
                'label' => __('Enabled'),
                'title' => __('Enabled'),
                'value' => 0,
                'options' => $this->yesNo->toArray(),
            ]
        );

        $fieldset->addType(
            'status',
            \Magetrend\Scheduler\Block\Adminhtml\Form\Element\Status::class
        );

        $fieldset->addType(
            'clock',
            \Magetrend\Scheduler\Block\Adminhtml\Form\Element\Clock::class
        );
        $fieldset->addField(
            ScheduleInterface::STATUS,
            $model->getId()?'status':'hidden',
            [
                'name' => ScheduleInterface::STATUS,
                'label' => __('Schedule Status'),
                'title' => __('Schedule Status'),
                'value' => '',
            ]
        );

        $fieldset->addField(
            'current_time',
            'clock',
            [
                'name' => 'current_time',
                'label' => __('Current Time'),
                'title' => __('Current Time'),
            ]
        );

        $fieldset->addField(
            ScheduleInterface::DATE_FROM,
            'date',
            [
                'name' => 'schedule['.ScheduleInterface::DATE_FROM.']',
                'label' => __('Start Date'),
                'title' => __('Start Date'),
                'required' => true,
                'class' => 'mt-sheduler-field-readonly',
                'showsTime' => true,
                'input_format' => \Magento\Framework\Stdlib\DateTime::DATE_INTERNAL_FORMAT,
                'date_format' => $dateFormat,
                'time_format' => $timeFormat,
            ]
        );

        $fieldset->addField(
            ScheduleInterface::DATE_TO,
            'date',
            [
                'name' => 'schedule['.ScheduleInterface::DATE_TO.']',
                'label' => __('End Date'),
                'title' => __('End Date'),
                'required' => false,
                'disabled' => false,
                'showsTime' => true,
                'input_format' => \Magento\Framework\Stdlib\DateTime::DATE_INTERNAL_FORMAT,
                'date_format' => $dateFormat,
                'time_format' => $timeFormat,
                'note' => __('(Optional) After this date  all the changes, made by schedule, will be reverted back')
            ]
        );

        if (in_array($type, [ScheduleInterface::TYPE_PRODUCT, ScheduleInterface::TYPE_CATEGORY])) {
            $fieldset->addField(
                'store_ids',
                'multiselect',
                [
                    'name' => 'schedule[store_ids][]',
                    'label' => __('Stores'),
                    'title' => __('Stores'),
                    'required' => true,
                    'values' => $this->store->getStoreValuesForForm(false, true),
                    'value' => 0,
                ]
            );
        }

        $fieldset->addField(
            ScheduleInterface::NAME,
            'text',
            [
                'name' => 'schedule['.ScheduleInterface::NAME.']',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true,
                'disabled' => false
            ]
        );

        $fieldset->addField(
            ScheduleInterface::DESCRIPTION,
            'textarea',
            [
                'name' => 'schedule['.ScheduleInterface::DESCRIPTION.']',
                'label' => __('Description'),
                'title' => __('description'),
                'required' => false,
                'disabled' => false
            ]
        );

        if ($model) {
            $formData = $model->getData();
            if ($type = $this->_request->getParam('type')) {
                $formData['type'] = $type;
            }

            $formData['status'] = $model->getFrontendStatus();
            $formData['date_from'] = $model->getDateFrom(true);
            $formData['date_to'] = $model->getDateTo(true);
            $formData['schedule_id'] = $model->getId();
            $formData['store_ids'] = $model->getStores();
            $form->setValues($formData);
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('General Settings');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('General Settings');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
