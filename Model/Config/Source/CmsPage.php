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
 * Cms pages source class
 */
class CmsPage extends \Magento\Cms\Model\Config\Source\Page
{
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
                foreach ($collection as $cmsPage) {
                    $this->options[] = [
                        'value' => $cmsPage->getId(),
                        'label' => $cmsPage->getTitle(),
                    ];
                }
            }
        }
        return $this->options;
    }
}
