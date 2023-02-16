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

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\NoSuchEntityException;
use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Duplicate schedule controller class
 */
class Duplicate extends \Magetrend\Scheduler\Controller\Adminhtml\Schedule
{
    /**
     * Duplicate action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = $this->scheduleManagement->duplicate($id);
                $this->messageManager->addSuccess(__('The schedule was cloned.'));
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->logger->critical($e);
                $this->messageManager->addException($e, __('Something went wrong while cloning the schedule.'));
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}