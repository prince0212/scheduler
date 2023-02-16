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

namespace  Magetrend\Scheduler\Controller\Adminhtml\Schedule;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Product grid controller class
 */
class ProductGrid extends \Magetrend\Scheduler\Controller\Adminhtml\Schedule
{
    /**
     * Render product grid action
     *
     * @return void
     */
    public function execute()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = $this->scheduleRepository->get($id);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addError(__('This schedule no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        } else {
            $model = $this->scheduleRepository->getNew();
        }

        $this->registry->register('current_model', $model);

        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}
