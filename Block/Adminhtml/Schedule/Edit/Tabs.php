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

namespace Magetrend\Scheduler\Block\Adminhtml\Schedule\Edit;

use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Schedule edit tabs block class
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @var \Magento\Framework\Registry
     */
    public $registry;

    /**
     * Tabs constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->registry = $registry;
        parent::__construct($context, $jsonEncoder, $authSession, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('schedule_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Schedule Information'));
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $type = $this->_request->getParam('type');
        if (empty($type)) {
            $model = $this->registry->registry('current_model');
            $type = $model->getType();
        }

        if ($type == ScheduleInterface::TYPE_PRODUCT) {
            $this->addGeneralTab();
            $this->addProductAttributeTab();
            $this->addProductTab();
            $this->addCatalogConditionTab();
        }

        if ($type == ScheduleInterface::TYPE_CMS_PAGE) {
            $this->addGeneralTab();
            $this->addCmsPageAttributeTab();
            $this->addCmsPageTab();
        }

        if ($type == ScheduleInterface::TYPE_CMS_BLOCK) {
            $this->addGeneralTab();
            $this->addCmsBlockAttributeTab();
            $this->addCmsBlockTab();
        }

        if ($type == ScheduleInterface::TYPE_CATEGORY) {
            $this->addGeneralTab();
            $this->addCategoryAttributeTab();
            $this->addCategoryTab();
        }

        return parent::_beforeToHtml();
    }

    /**
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addGeneralTab()
    {
        $this->addTab(
            'general_section',
            [
                'label' => __('General Settings'),
                'title' => __('General Settings'),
                'active' => true,
                'content' => $this->getLayout()->createBlock(
                    'Magetrend\Scheduler\Block\Adminhtml\Schedule\Edit\Tab\General'
                )->toHtml()
            ]
        );
    }

    /**
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addProductAttributeTab()
    {
        $this->addTab(
            'product_attribute_section',
            [
                'label' => __('Actions'),
                'title' => __('Actions'),
                'active' => false,
                'content' => $this->getLayout()->createBlock(
                    'Magetrend\Scheduler\Block\Adminhtml\Schedule\Edit\Tab\ProductAttributes'
                )->toHtml()
            ]
        );
    }

    /**
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addProductTab()
    {
        $this->addTab(
            'products',
            [
                'label' => __('Products'),
                'title' => __('Products'),
                'active' => false,
                'content' => $this->getLayout()->createBlock(
                    'Magetrend\Scheduler\Block\Adminhtml\Schedule\Edit\Tab\Product\Assign'
                )->toHtml()
            ]
        );
    }

    /**
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addCatalogConditionTab()
    {
        $this->addTab(
            'conditions',
            [
                'label' => __('Products by Conditions'),
                'title' => __('Products by Conditions'),
                'active' => false,
                'content' => $this->getLayout()->createBlock(
                    'Magetrend\Scheduler\Block\Adminhtml\Schedule\Edit\Tab\Condition'
                )->toHtml()
            ]
        );
    }

    /**
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addCmsPageAttributeTab()
    {
        $this->addTab(
            'cms_pages_attributes',
            [
                'label' => __('Actions'),
                'title' => __('Actions'),
                'active' => false,
                'content' => $this->getLayout()->createBlock(
                    'Magetrend\Scheduler\Block\Adminhtml\Schedule\Edit\Tab\CmsPageAttributes'
                )->toHtml()
            ]
        );
    }

    /**
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addCmsPageTab()
    {
        $this->addTab(
            'cms_page',
            [
                'label' => __('CMS Pages'),
                'title' => __('CMS Pages'),
                'active' => false,
                'content' => $this->getLayout()->createBlock(
                    'Magetrend\Scheduler\Block\Adminhtml\Schedule\Edit\Tab\CmsPage'
                )->toHtml()
            ]
        );
    }

    /**
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addCmsBlockAttributeTab()
    {
        $this->addTab(
            'cms_block_attributes',
            [
                'label' => __('Actions'),
                'title' => __('Actions'),
                'active' => false,
                'content' => $this->getLayout()->createBlock(
                    'Magetrend\Scheduler\Block\Adminhtml\Schedule\Edit\Tab\CmsBlockAttributes'
                )->toHtml()
            ]
        );
    }

    /**
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addCmsBlockTab()
    {
        $this->addTab(
            'cms_block',
            [
                'label' => __('CMS Blocks'),
                'title' => __('CMS Blocks'),
                'active' => false,
                'content' => $this->getLayout()->createBlock(
                    'Magetrend\Scheduler\Block\Adminhtml\Schedule\Edit\Tab\CmsBlock'
                )->toHtml()
            ]
        );
    }

    /**
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addCategoryAttributeTab()
    {
        $this->addTab(
            'category_attributes',
            [
                'label' => __('Actions'),
                'title' => __('Actions'),
                'active' => false,
                'content' => $this->getLayout()->createBlock(
                    'Magetrend\Scheduler\Block\Adminhtml\Schedule\Edit\Tab\CategoryAttributes'
                )->toHtml()
            ]
        );
    }

    /**
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addCategoryTab()
    {
        $this->addTab(
            'category',
            [
                'label' => __('Categories'),
                'title' => __('Categories'),
                'active' => false,
                'content' => $this->getLayout()->createBlock(
                    'Magetrend\Scheduler\Block\Adminhtml\Schedule\Edit\Tab\Category'
                )->toHtml()
            ]
        );
    }
}
