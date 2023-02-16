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

namespace Magetrend\Scheduler\Model\Config\Source;

/**
 * Cms blocks source class
 */
class CmsBlock implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var array
     */
    public $options;

    /**
     * @var \Magento\Cms\Model\ResourceModel\Block\CollectionFactory
     */
    public $collectionFactory;

    /**
     * CmsBlock constructor.
     * @param \Magento\Cms\Model\ResourceModel\Block\CollectionFactory $collectionFactory
     */
    public function __construct(
        \Magento\Cms\Model\ResourceModel\Block\CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * To option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = [];
            $collection = $this->collectionFactory->create();
            if ($collection->getSize() > 0) {
                foreach ($collection as $block) {
                    $this->options[] = [
                        'value' => $block->getId(),
                        'label' => $block->getTitle(),
                    ];
                }
            }
        }
        return $this->options;
    }
}
