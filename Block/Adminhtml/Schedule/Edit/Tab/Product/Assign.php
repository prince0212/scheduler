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

namespace Magetrend\Scheduler\Block\Adminhtml\Schedule\Edit\Tab\Product;

/**
 * Assign products tab block class
 */
class Assign extends \Magento\Backend\Block\Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'Magetrend_Scheduler::schedule/edit/tab/product/assign.phtml';

    /**
     * @var \Magento\Catalog\Block\Adminhtml\Category\Tab\Product
     */
    public $blockGrid;

    /**
     * @var \Magento\Framework\Registry
     */
    public $registry;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    public $jsonEncoder;

    /**
     * AssignProducts constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve instance of grid block
     *
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBlockGrid()
    {
        if (null === $this->blockGrid) {
            $this->blockGrid = $this->getLayout()->createBlock(
                \Magetrend\Scheduler\Block\Adminhtml\Schedule\Edit\Tab\Product\Grid::class,
                'schedule.product.grid'
            );
        }
        return $this->blockGrid;
    }

    /**
     * Return HTML of grid block
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getBlockGrid()->toHtml();
    }

    /**
     * Retruns product ids in json
     * @return string
     */
    public function getProductsJson()
    {
        if (!$this->getSchedule()->getId()) {
            return '{}';
        }

        $products = $this->getSchedule()->getInstance()->getProducts();
        if (!empty($products)) {
            $data = [];
            foreach ($products as $id) {
                $data[$id] = 1;
            }
            return $this->jsonEncoder->encode($data);
        }
        return '{}';
    }

    /**
     * Retrieve current schedule
     *
     * @return \Magetrend\Scheduler\Model\Schedule
     */
    public function getSchedule()
    {
        return $this->registry->registry('current_model');
    }

    /**
     * Returns read only status
     * @return bool
     */
    public function readOnly()
    {
        return !$this->getSchedule()->canEdit();
    }
}
