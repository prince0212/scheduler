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
use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 *  Category attributes tab block class
 */
class CategoryAttributes extends Generic implements TabInterface
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
     * @var \Magetrend\Scheduler\Model\Config\Source\PriceAction
     */
    public $priceAction;

    /**
     * @var \Magetrend\Scheduler\Model\Config\Source\EnabledDisabled
     */
    public $enabledDisabled;

    /**
     * @var \Magetrend\Scheduler\Model\Config\Source\StockStatus
     */
    public $stockStatus;

    /**
     * CmsBlockAttributes constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magento\Config\Model\Config\Source\Yesno $yesno
     * @param \Magetrend\Scheduler\Model\Config\Source\PriceAction $priceAction
     * @param \Magetrend\Scheduler\Model\Config\Source\EnabledDisabled $enabledDisabled
     * @param \Magetrend\Scheduler\Model\Config\Source\StockStatus $stockStatus
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Config\Model\Config\Source\Yesno $yesno,
        \Magetrend\Scheduler\Model\Config\Source\PriceAction $priceAction,
        \Magetrend\Scheduler\Model\Config\Source\EnabledDisabled $enabledDisabled,
        \Magetrend\Scheduler\Model\Config\Source\StockStatus $stockStatus,
        array $data = []
    ) {
        $this->store = $systemStore;
        $this->yesNo = $yesno;
        $this->priceAction = $priceAction;
        $this->enabledDisabled = $enabledDisabled;
        $this->stockStatus = $stockStatus;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_model');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('page_');

        $this->addStatusFields($form);

        $htmlIdPrefix = $form->getHtmlIdPrefix();
        $dependence = $this->getLayout()->createBlock(\Magento\Backend\Block\Widget\Form\Element\Dependence::class);

        $this->addStatusDependencies($dependence, $htmlIdPrefix);

        $this->setChild('form_after', $dependence);

        if ($model) {
            $formData = $model->getData();
            if ($type = $this->_request->getParam('type')) {
                $formData['type'] = $type;
            }

            $form->setValues($formData);
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Add status fields
     * @param $form
     */
    public function addStatusFields($form)
    {
        $fieldset2 = $form->addFieldset('status_fieldset', ['legend' => __('Category Status')]);
        $fieldset2->addField(
            ScheduleInterface::CATEGORY_STATUS,
            'select',
            [
                'name' => 'schedule['.ScheduleInterface::CATEGORY_STATUS.']',
                'label' => __('Update Status'),
                'title' => __('Update Status'),
                'value' => 0,
                'options' => $this->yesNo->toArray(),
                'class' => 'mt-sheduler-field-readonly',
            ]
        );

        $fieldset2->addField(
            ScheduleInterface::CATEGORY_NEW_STATUS,
            'select',
            [
                'name' => 'schedule['.ScheduleInterface::CATEGORY_NEW_STATUS.']',
                'label' => __('New Status'),
                'title' => __('New Status'),
                'value' => 0,
                'options' => $this->enabledDisabled->toArray(),
                'class' => 'mt-sheduler-field-readonly',
            ]
        );
    }

    /**
     * Add status field dependency
     * @param $dependence
     * @param $htmlIdPrefix
     */
    public function addStatusDependencies($dependence, $htmlIdPrefix)
    {
        $dependence->addFieldMap(
            $htmlIdPrefix.ScheduleInterface::CATEGORY_STATUS,
            ScheduleInterface::CATEGORY_STATUS
        )->addFieldMap(
            $htmlIdPrefix.ScheduleInterface::CATEGORY_NEW_STATUS,
            ScheduleInterface::CATEGORY_NEW_STATUS
        )->addFieldDependence(
            ScheduleInterface::CATEGORY_NEW_STATUS,
            ScheduleInterface::CATEGORY_STATUS,
            1
        );
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Actions');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Actions');
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

    /**
     * Returns current schedule
     * @return \Magetrend\Scheduler\Model\Schedule
     */
    public function getSchedule()
    {
        return $this->_coreRegistry->registry('current_model');
    }
}
