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

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Element\Dependence;
use \Magento\Backend\Block\Widget\Form\Generic;
use \Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;
use Magetrend\Scheduler\Api\Data\ScheduleInterface;
use Magetrend\Scheduler\Model\Config\Source\EnabledDisabled;
use Magetrend\Scheduler\Model\Config\Source\PriceAction;
use Magetrend\Scheduler\Model\Config\Source\StockStatus;
use Magetrend\Scheduler\Model\Schedule;

/**
 *  Cms block attributes tab block class
 */
class CmsBlockAttributes extends Generic implements TabInterface
{
    /**
     * @var Store
     */
    public $store;

    /**
     * @var Yesno
     */
    public $yesNo;

    /**
     * @var PriceAction
     */
    public $priceAction;

    /**
     * @var EnabledDisabled
     */
    public $enabledDisabled;

    /**
     * @var StockStatus
     */
    public $stockStatus;

    /**
     * CmsBlockAttributes constructor.
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param Yesno $yesno
     * @param PriceAction $priceAction
     * @param EnabledDisabled $enabledDisabled
     * @param StockStatus $stockStatus
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        Yesno $yesno,
        PriceAction $priceAction,
        EnabledDisabled $enabledDisabled,
        StockStatus $stockStatus,
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
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_model');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('page_');

        $this->addStatusFields($form);

        $htmlIdPrefix = $form->getHtmlIdPrefix();
        $dependence = $this->getLayout()->createBlock(Dependence::class);

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
        $fieldset2 = $form->addFieldset('status_fieldset', ['legend' => __('CMS Block Status')]);
        $fieldset2->addField(
            ScheduleInterface::CMS_BLOCK_STATUS,
            'select',
            [
                'name' => 'schedule['.ScheduleInterface::CMS_BLOCK_STATUS.']',
                'label' => __('Update Status'),
                'title' => __('Update Status'),
                'value' => 0,
                'options' => $this->yesNo->toArray(),
                'class' => 'mt-sheduler-field-readonly',
            ]
        );

        $fieldset2->addField(
            ScheduleInterface::CMS_BLOCK_NEW_STATUS,
            'select',
            [
                'name' => 'schedule['.ScheduleInterface::CMS_BLOCK_NEW_STATUS.']',
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
            $htmlIdPrefix.ScheduleInterface::CMS_BLOCK_STATUS,
            ScheduleInterface::CMS_BLOCK_STATUS
        )->addFieldMap(
            $htmlIdPrefix.ScheduleInterface::CMS_BLOCK_NEW_STATUS,
            ScheduleInterface::CMS_BLOCK_NEW_STATUS
        )->addFieldDependence(
            ScheduleInterface::CMS_BLOCK_NEW_STATUS,
            ScheduleInterface::CMS_BLOCK_STATUS,
            1
        );
    }

    /**
     * Prepare label for tab
     *
     * @return Phrase
     */
    public function getTabLabel()
    {
        return __('Actions');
    }

    /**
     * Prepare title for tab
     *
     * @return Phrase
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
     * @return Schedule
     */
    public function getSchedule()
    {
        return $this->_coreRegistry->registry('current_model');
    }
}
