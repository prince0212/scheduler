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
 * Product attribute tab block class
 */
class ProductAttributes extends Generic implements TabInterface
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
     * ProductAttributes constructor.
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

        $this->addSpecialPriceFields($form);
        $this->addStatusFields($form);
        $this->addStockStatusFields($form);
        $this->addCategoriesFields($form);

        $htmlIdPrefix = $form->getHtmlIdPrefix();
        $dependence = $this->getLayout()->createBlock(\Magento\Backend\Block\Widget\Form\Element\Dependence::class);

        $this->addSpecialPriceDependencies($dependence, $htmlIdPrefix);
        $this->addStatusDependencies($dependence, $htmlIdPrefix);
        $this->addStockStatusDependencies($dependence, $htmlIdPrefix);
        $this->addCategoriesDependencies($dependence, $htmlIdPrefix);

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
     * Add update special price field
     * @param $form
     */
    public function addSpecialPriceFields($form)
    {
        $fieldset = $form->addFieldset('special_price_fieldset', ['legend' => __('Product Special Price')]);
        $fieldset->addField(
            ScheduleInterface::PRODUCT_SPECIAL_PRICE,
            'select',
            [
                'name' => 'schedule['.ScheduleInterface::PRODUCT_SPECIAL_PRICE.']',
                'label' => __('Update Product Price'),
                'title' => __('Update Product Price'),
                'value' => 0,
                'options' => $this->yesNo->toArray(),
                'class' => 'mt-sheduler-field-readonly',
            ]
        );

        $fieldset->addField(
            ScheduleInterface::PRODUCT_SPECIAL_PRICE_ACTION,
            'select',
            [
                'name' => 'schedule['.ScheduleInterface::PRODUCT_SPECIAL_PRICE_ACTION.']',
                'label' => __('Action on Special Price'),
                'title' => __('Action on Special Price'),
                'value' => 0,
                'options' => $this->priceAction->toArray(),
                'class' => 'mt-sheduler-field-readonly',
            ]
        );

        $fieldset->addField(
            ScheduleInterface::PRODUCT_SPECIAL_PRICE_VALUE,
            'text',
            [
                'name' => 'schedule['.ScheduleInterface::PRODUCT_SPECIAL_PRICE_VALUE.']',
                'label' => __('Value'),
                'title' => __('Value'),
                'style' => 'width: 100px',
                'class' => 'mt-sheduler-field-readonly',
            ]
        );
    }

    /**
     * Add update status fields
     * @param $form
     */
    public function addStatusFields($form)
    {
        $fieldset2 = $form->addFieldset('status_fieldset', ['legend' => __('Product Status')]);
        $fieldset2->addField(
            ScheduleInterface::PRODUCT_STATUS,
            'select',
            [
                'name' => 'schedule['.ScheduleInterface::PRODUCT_STATUS.']',
                'label' => __('Update Product Status'),
                'title' => __('Update Product Status'),
                'value' => 0,
                'options' => $this->yesNo->toArray(),
                'class' => 'mt-sheduler-field-readonly',
            ]
        );

        $fieldset2->addField(
            ScheduleInterface::PRODUCT_NEW_STATUS,
            'select',
            [
                'name' => 'schedule['.ScheduleInterface::PRODUCT_NEW_STATUS.']',
                'label' => __('New Status'),
                'title' => __('New Status'),
                'value' => 0,
                'options' => $this->enabledDisabled->toArray(),
                'class' => 'mt-sheduler-field-readonly',
            ]
        );
    }

    /**
     * Add update stock status fields
     * @param $form
     */
    public function addStockStatusFields($form)
    {
        $fieldset3 = $form->addFieldset('product_stock_status_fieldset', ['legend' => __('Product Stock Status')]);
        $fieldset3->addField(
            ScheduleInterface::PRODUCT_STOCK_STATUS,
            'select',
            [
                'name' => 'schedule['.ScheduleInterface::PRODUCT_STOCK_STATUS.']',
                'label' => __('Update Product Stock Status'),
                'title' => __('Update Product Stock Status'),
                'value' => 0,
                'options' => $this->yesNo->toArray(),
                'class' => 'mt-sheduler-field-readonly',
            ]
        );

        $fieldset3->addField(
            ScheduleInterface::PRODUCT_NEW_STOCK_STATUS,
            'select',
            [
                'name' => 'schedule['.ScheduleInterface::PRODUCT_NEW_STOCK_STATUS.']',
                'label' => __('New Stock Status'),
                'title' => __('New Stock Status'),
                'value' => 0,
                'options' => $this->stockStatus->toArray(),
                'class' => 'mt-sheduler-field-readonly',
            ]
        );
    }

    /**
     * Add update categories fields
     * @param $form
     */
    public function addCategoriesFields($form)
    {
        $fieldset4 = $form->addFieldset('product_categories_fieldset', ['legend' => __('Product Categories')]);
        $fieldset4->addField(
            ScheduleInterface::PRODUCT_CATEGORIES,
            'select',
            [
                'name' => 'schedule['.ScheduleInterface::PRODUCT_CATEGORIES.']',
                'label' => __('Update Product Categories'),
                'title' => __('Update Product Categories'),
                'value' => 0,
                'options' => $this->yesNo->toArray(),
                'class' => 'mt-sheduler-field-readonly',
            ]
        );

        $fieldset4->addType(
            'category',
            \Magetrend\Scheduler\Block\Adminhtml\Form\Element\Category::class
        );

        $fieldset4->addField(
            ScheduleInterface::PRODUCT_ADD_TO_CATEGORIES,
            'category',
            [
                'name' => 'schedule['.ScheduleInterface::PRODUCT_ADD_TO_CATEGORIES.']',
                'label' => __('Add to Categories'),
                'title' => __('Add to Categories'),
                'class' => 'mt-sheduler-field-readonly',
            ]
        );

        $fieldset4->addField(
            ScheduleInterface::PRODUCT_REMOVE_FROM_CATEGORIES,
            'category',
            [
                'name' => 'schedule['.ScheduleInterface::PRODUCT_REMOVE_FROM_CATEGORIES.']',
                'label' => __('Remove from Categories'),
                'title' => __('Remove from Categories'),
                'class' => 'mt-sheduler-field-readonly',
            ]
        );
    }

    /**
     * Add special price update fields dependency
     * @param $dependence
     * @param $htmlIdPrefix
     */
    public function addSpecialPriceDependencies($dependence, $htmlIdPrefix)
    {
        $dependence->addFieldMap(
            $htmlIdPrefix.ScheduleInterface::PRODUCT_SPECIAL_PRICE,
            ScheduleInterface::PRODUCT_SPECIAL_PRICE
        )->addFieldMap(
            $htmlIdPrefix.ScheduleInterface::PRODUCT_SPECIAL_PRICE_ACTION,
            ScheduleInterface::PRODUCT_SPECIAL_PRICE_ACTION
        )->addFieldMap(
            $htmlIdPrefix.ScheduleInterface::PRODUCT_SPECIAL_PRICE_VALUE,
            ScheduleInterface::PRODUCT_SPECIAL_PRICE_VALUE
        )->addFieldDependence(
            ScheduleInterface::PRODUCT_SPECIAL_PRICE_ACTION,
            ScheduleInterface::PRODUCT_SPECIAL_PRICE,
            1
        )->addFieldDependence(
            ScheduleInterface::PRODUCT_SPECIAL_PRICE_VALUE,
            ScheduleInterface::PRODUCT_SPECIAL_PRICE,
            1
        );
    }

    /**
     * Add status update fields dependency
     * @param $dependence
     * @param $htmlIdPrefix
     */
    public function addStatusDependencies($dependence, $htmlIdPrefix)
    {
        $dependence->addFieldMap(
            $htmlIdPrefix.ScheduleInterface::PRODUCT_STATUS,
            ScheduleInterface::PRODUCT_STATUS
        )->addFieldMap(
            $htmlIdPrefix.ScheduleInterface::PRODUCT_NEW_STATUS,
            ScheduleInterface::PRODUCT_NEW_STATUS
        )->addFieldDependence(
            ScheduleInterface::PRODUCT_NEW_STATUS,
            ScheduleInterface::PRODUCT_STATUS,
            1
        );
    }

    /**
     * Add stock status update fields dependency
     * @param $dependence
     * @param $htmlIdPrefix
     */
    public function addStockStatusDependencies($dependence, $htmlIdPrefix)
    {
        $dependence->addFieldMap(
            $htmlIdPrefix.ScheduleInterface::PRODUCT_STOCK_STATUS,
            ScheduleInterface::PRODUCT_STOCK_STATUS
        )->addFieldMap(
            $htmlIdPrefix.ScheduleInterface::PRODUCT_NEW_STOCK_STATUS,
            ScheduleInterface::PRODUCT_NEW_STOCK_STATUS
        )->addFieldDependence(
            ScheduleInterface::PRODUCT_NEW_STOCK_STATUS,
            ScheduleInterface::PRODUCT_STOCK_STATUS,
            1
        );
    }

    /**
     * Add categories update fields dependency
     * @param $dependence
     * @param $htmlIdPrefix
     */
    public function addCategoriesDependencies($dependence, $htmlIdPrefix)
    {
        $dependence->addFieldMap(
            $htmlIdPrefix.ScheduleInterface::PRODUCT_CATEGORIES,
            ScheduleInterface::PRODUCT_CATEGORIES
        )->addFieldMap(
            $htmlIdPrefix.ScheduleInterface::PRODUCT_ADD_TO_CATEGORIES,
            ScheduleInterface::PRODUCT_ADD_TO_CATEGORIES
        )->addFieldMap(
            $htmlIdPrefix.ScheduleInterface::PRODUCT_REMOVE_FROM_CATEGORIES,
            ScheduleInterface::PRODUCT_REMOVE_FROM_CATEGORIES
        )->addFieldDependence(
            ScheduleInterface::PRODUCT_ADD_TO_CATEGORIES,
            ScheduleInterface::PRODUCT_CATEGORIES,
            1
        )->addFieldDependence(
            ScheduleInterface::PRODUCT_REMOVE_FROM_CATEGORIES,
            ScheduleInterface::PRODUCT_CATEGORIES,
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

    /**
     * Returns current schedule
     * @return \Magetrend\Scheduler\Model\Schedule
     */
    public function getSchedule()
    {
        return $this->_coreRegistry->registry('current_model');
    }
}
