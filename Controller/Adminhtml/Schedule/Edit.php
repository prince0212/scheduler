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

namespace Magetrend\Scheduler\Controller\Adminhtml\Schedule;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Edit schedule controller class
 */
class Edit extends \Magetrend\Scheduler\Controller\Adminhtml\Schedule
{

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if ($id) {
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

        $data = $this->_getSession()->getFormData();
        if ($data) {
            $model->addData($data['schedule']);
        }

        $this->registry->register('current_model', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magetrend_Scheduler::schedule')
            ->addBreadcrumb(__('Scheduler'), __('Scheduler'))
            ->addBreadcrumb(
                $id ? __('Edit Schedule') : __('New Schedule'),
                $id ? __('Edit Schedule') : __('New Schedule')
            );
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getName() : __('New Schedule'));

        return $resultPage;
    }
}
