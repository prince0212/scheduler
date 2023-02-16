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

/**
 * Mass schedule disable controller class
 */
class MassDisable extends \Magetrend\Scheduler\Controller\Adminhtml\Schedule
{
    /**
     * Mass schedule disable action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $idList = $this->getRequest()->getParam('schedules', []);
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!empty($idList)) {
            try {
                $this->scheduleManagement->massAction($idList, function ($schedule) {
                    $schedule->setIsActive(0);
                });
                // display success message
                $this->messageManager->addSuccess(__('The schedules have been disabled.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->messageManager->addError(__('We can\'t find a item to disable.'));
        return $resultRedirect->setPath('*/*/');
    }
}
