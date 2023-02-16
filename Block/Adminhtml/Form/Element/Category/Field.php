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

namespace Magetrend\Scheduler\Block\Adminhtml\Form\Element\Category;

/**
 * Category select field block class
 */
class Field extends \Magento\Backend\Block\Template
{
    /**
     * @var string
     */
    protected $_template = 'Magetrend_Scheduler::form/element/category/field.phtml';

    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    protected $formKey;

    /**
     * Field constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        $this->_objectManager = $objectManager;
        $this->formKey = $context->getFormKey();
        parent::__construct($context, $data);
    }

    /**
     * Prepare layout
     *
     * @return this
     */
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    /**
     * Returns categories tree
     * @return string
     */
    public function getCategoriesTree()
    {
        $categories = $this->_objectManager->create(
            'Magento\Catalog\Ui\Component\Product\Form\Categories\Options'
        )->toOptionArray();
        return json_encode($categories);
    }

    /**
     * Returns selected categories
     * @return string
     */
    public function getDefault()
    {
        $value = $this->getElement()->getValue();
        if (empty($value)) {
            return '[]';
        }

        if (is_string($value)) {
            $value = explode(',', $value);
        }

        return json_encode($value);
    }
}
