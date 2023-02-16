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
 *  Cms page tab block class
 */
class CmsPage extends Generic implements TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    public $store;

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
     * @var \Magento\Cms\Model\Config\Source\Page
     */
    public $cmsPageSource;

    /**
     * CmsPage constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magetrend\Scheduler\Model\Config\Source\CmsPage $cmsPageSource
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magetrend\Scheduler\Model\Config\Source\CmsPage $cmsPageSource,
        array $data = []
    ) {
        $this->store = $systemStore;
        $this->cmsPageSource = $cmsPageSource;
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

        $fieldset2 = $form->addFieldset('status_fieldset', ['legend' => __('CMS Pages')]);
        $fieldset2->addField(
            ScheduleInterface::CMS_PAGE_IDS,
            'multiselect',
            [
                'name' => 'schedule['.ScheduleInterface::CMS_PAGE_IDS.']',
                'label' => __('CMS Pages'),
                'title' => __('CMS Pages'),
                'value' => 0,
                'values' => $this->cmsPageSource->toOptionArray(),
                'class' => 'mt-sheduler-field-readonly',
            ]
        );

        if ($model->getId()) {
            $formData = $model->getData();
            $formData[ScheduleInterface::CMS_PAGE_IDS] = $model->getInstance()->getCmsPageIds();
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
        return __('Cms Pages');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Cms Pages');
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
