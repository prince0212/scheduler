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
 *  Cms block tab block class
 */
class CmsBlock extends Generic implements TabInterface
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
    public $cmsBlockSource;

    /**
     * CmsPage constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magetrend\Scheduler\Model\Config\Source\CmsBlock $cmsBlockSource
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magetrend\Scheduler\Model\Config\Source\CmsBlock $cmsBlockSource,
        array $data = []
    ) {
        $this->store = $systemStore;
        $this->cmsBlockSource = $cmsBlockSource;
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

        $fieldset2 = $form->addFieldset('status_fieldset', ['legend' => __('CMS Blocks')]);
        $fieldset2->addField(
            ScheduleInterface::CMS_BLOCK_IDS,
            'multiselect',
            [
                'name' => 'schedule['.ScheduleInterface::CMS_BLOCK_IDS.']',
                'label' => __('CMS Blocks'),
                'title' => __('CMS Blocks'),
                'value' => 0,
                'values' => $this->cmsBlockSource->toOptionArray(),
                'class' => 'mt-sheduler-field-readonly',
            ]
        );

        if ($model->getId()) {
            $formData = $model->getData();
            $formData[ScheduleInterface::CMS_BLOCK_IDS] = $model->getInstance()->getCmsBlockIds();
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
        return __('Cms Blocks');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Cms Blocks');
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
